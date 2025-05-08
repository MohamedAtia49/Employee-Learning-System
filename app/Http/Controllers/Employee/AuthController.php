<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email,exists:employees,email',
            'password' => 'required',
        ]);

        if (Auth::guard('employee')->attempt($credentials)) {
            return redirect('/employee/home');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('employee')->logout();
        return redirect('/employee/login');
    }
}
