<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => [
                'required',
                'string',
                'regex:/^\+?\d{10,15}$/', // Allows optional + prefix
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Remove all non-digit characters
                    $digits = preg_replace('/\D/', '', $value);

                    // Validate length after cleaning
                    if (strlen($digits) < 10 || strlen($digits) > 15) {
                        $fail('The mobile number must be between 10 and 15 digits.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:16',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{16,}$/'
            ],
            'honeypot' => ['present', 'max:0']
        ], [
            'password.regex' => 'The password must contain at least: 1 uppercase, 1 lowercase, 1 number and 1 special character',
            'honeypot.max' => 'Invalid form submission'
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
