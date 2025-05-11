<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        // Check if order is already shipped
        if ($order->status === 'shipped') {
            return back()->with('error', 'Cannot cancel an order that has already been shipped. Please contact customer support.');
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

        return back()->with('success', 'Your order has been canceled successfully.');
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

        Log::info('Setting password for user', [
            'user_id' => $user->id,
            'has_google_id' => !empty($user->google_id),
            'has_password' => !empty($user->password),
            'request_data' => $request->all()
        ]);

        if (!$user->google_id) {
            Log::warning('User attempted to set password but is not a Google user');
            return redirect()->back()->with('error', 'This action is only available for Google accounts.');
        }

        try {
            $validated = $request->validate([
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

            Log::info('Password validation passed', ['user_id' => $user->id]);

            $user->password = Hash::make($request->password);
            $user->save();

            Log::info('Password set successfully for user', [
                'user_id' => $user->id,
                'has_password_now' => !empty($user->password)
            ]);

            return redirect()->back()->with('success', 'Password set successfully. You can now log in with either Google or your email/password.');
        } catch (\Exception $e) {
            Log::error('Error setting password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withErrors(['password' => $e->getMessage()])->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::find(Auth::id());

        // Different validation rules based on authentication type
        if ($user->google_id && !$user->password) {
            // Google user setting password for the first time
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
        } else {
            // Regular user or Google user with password changing password
            $request->validate([
                'current_password' => 'required',
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
                'password.different' => 'The new password must be different from your current password',
            ]);

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect']);
            }
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        Log::info('Account deletion requested', ['user_id' => $userId]);

        // Verify password for users with password set
        if (!$user->google_id || ($user->google_id && $user->password)) {
            $request->validate([
                'password' => 'required',
            ]);

            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Account deletion failed: incorrect password', ['user_id' => $userId]);
                return back()->withErrors(['password' => 'Password is incorrect']);
            }
        }

        try {
            // Logout the user
            Auth::logout();

            // Delete the user account using the User model
            \App\Models\User::destroy($userId);

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('Account successfully deleted', ['user_id' => $userId]);
            return redirect()->route('home')->with('status', 'Your account has been permanently deleted.');
        } catch (\Exception $e) {
            Log::error('Error deleting account', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('user.account.details')->with('error', 'An error occurred while deleting your account. Please try again.');
        }
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        return view('user.addresses', compact('addresses'));
    }

    public function createAddress()
    {
        return view('user.address-create');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:11',
            'postal' => 'required|numeric|digits:4',
            'barangay' => 'required',
            'city' => 'required',
            'province' => 'required',
            'region' => 'required',
            'address' => 'required',
            'landmark' => 'required',
        ]);

        $address = new Address();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->postal = $request->postal;
        $address->barangay = $request->barangay;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->region = $request->region;
        $address->address = $request->address;
        $address->landmark = $request->landmark;
        $address->country = 'Philippines';
        $address->user_id = Auth::user()->id;

        // If this is the first address, make it default
        if (Address::where('user_id', Auth::user()->id)->count() == 0) {
            $address->isdefault = true;
        }

        $address->save();

        return redirect()->route('user.addresses')->with('success', 'Address added successfully');
    }

    public function editAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        return view('user.address-edit', compact('address'));
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:11',
            'postal' => 'required|numeric|digits:4',
            'barangay' => 'required',
            'city' => 'required',
            'province' => 'required',
            'region' => 'required',
            'address' => 'required',
            'landmark' => 'required',
        ]);

        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->postal = $request->postal;
        $address->barangay = $request->barangay;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->region = $request->region;
        $address->address = $request->address;
        $address->landmark = $request->landmark;
        $address->save();

        return redirect()->route('user.addresses')->with('success', 'Address updated successfully');
    }

    public function setDefaultAddress($id)
    {
        // First, set all addresses to non-default
        Address::where('user_id', Auth::user()->id)->update(['isdefault' => false]);

        // Then set the selected address as default
        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $address->isdefault = true;
        $address->save();

        return redirect()->route('user.addresses')->with('success', 'Default address updated');
    }

    public function deleteAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();

        // If deleting default address, set another as default if available
        if ($address->isdefault) {
            $address->delete();
            $newDefault = Address::where('user_id', Auth::user()->id)->first();
            if ($newDefault) {
                $newDefault->isdefault = true;
                $newDefault->save();
            }
        } else {
            $address->delete();
        }

        return redirect()->route('user.addresses')->with('success', 'Address deleted successfully');
    }

    public function checkAccountStatus()
    {
        $user = Auth::user();
        $status = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'has_google_id' => !empty($user->google_id),
            'has_password' => !empty($user->password),
            'account_type' => !empty($user->google_id) ?
                (!empty($user->password) ? 'Google with password' : 'Google without password') :
                'Regular account'
        ];

        return response()->json($status);
    }
}
