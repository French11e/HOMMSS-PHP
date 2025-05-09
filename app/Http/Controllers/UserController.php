<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $order_id)->first();
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order->id)->orderBy('created_at', 'DESC')->paginate(12);
            $transaction = Transaction::where('order_id', $order->id)->first();
            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        } else {
            return redirect()->route('login');
        }
    }

    public function order_cancel(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Check if order is already canceled
        if ($order->status === 'canceled') {
            return back()->with('info', 'This order has already been canceled.');
        }

        // Check if order is already delivered
        if ($order->status === 'delivered') {
            return back()->with('error', 'Cannot cancel an order that has already been delivered.');
        }

        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        // Update transaction status if exists
        $transaction = Transaction::where('order_id', $order->id)->first();
        if ($transaction) {
            $transaction->status = 'refunded';
            $transaction->save();
        }

        return back()->with('status', 'Order has been canceled successfully.');
    }

    public function accountDetails()
    {
        return view('user.account-details');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'mobile' => [
                'required',
                'string',
                'regex:/^\+?\d{10,15}$/',
                'unique:users,mobile,' . Auth::id(),
            ],
            'bio' => 'nullable|string|max:500',
        ]);

        $user = \App\Models\User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = \App\Models\User::find(Auth::id());

        // Delete old profile picture if exists
        if ($user->profile_picture && File::exists(public_path('uploads/profile/' . $user->profile_picture))) {
            File::delete(public_path('uploads/profile/' . $user->profile_picture));
        }

        // Process and save new profile picture
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Create directory if it doesn't exist
            if (!File::exists(public_path('uploads/profile'))) {
                File::makeDirectory(public_path('uploads/profile'), 0755, true);
            }

            // Process image with Intervention Image
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(300, 300);
            $img->save(public_path('uploads/profile/' . $filename));

            $user->profile_picture = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }

    public function setPassword(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user->google_id) {
            return redirect()->back()->with('error', 'This action is only available for Google accounts.');
        }

        $request->validate([
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.]).{12,}$/',
            ],
        ], [
            'password.regex' => 'The password must contain at least: 1 uppercase, 1 lowercase, 1 number and 1 special character',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password set successfully. You can now log in with either Google or your email/password.');
    }

    public function changePassword(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.]).{12,}$/',
                'different:current_password',
            ],
        ], [
            'password.regex' => 'The password must contain at least: 1 uppercase, 1 lowercase, 1 number and 1 special character',
            'current_password.current_password' => 'The current password is incorrect',
            'password.different' => 'The new password must be different from your current password',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
