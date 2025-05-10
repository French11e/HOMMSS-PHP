<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('name');

        $query = User::with('orders');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        }

        $users = $query->paginate(10);

        return view('admin.users', ['users' => $users]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'utype' => 'required|in:USR,ADM',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->utype = $request->utype;

        $user->save();

        return redirect()->route('admin.users')->with('status', 'User updated successfully!');
    }

    public function orders($id)
    {
        $user = User::findOrFail($id);
        $orders = $user->orders()->paginate(10);

        return view('admin.user_orders', ['user' => $user, 'orders' => $orders]);
    }
}
