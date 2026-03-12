<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        if (User::where('email', $validated['email'])) {
            back()->with('email', 'Email already exists');
        }
        unset($validated['password_confirmation']);
        User::create($validated);
        return redirect('login.show');
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            redirect()->route('welcome');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('login.show');
    }
}
