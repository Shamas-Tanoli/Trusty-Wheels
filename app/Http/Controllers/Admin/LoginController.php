<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    
    public function index()
    {
        return view('admin.content.authentications.auth-login-basic');
    }


    public function login(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required!',
            'email.email' => 'Enter a valid email!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 6 characters!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
           
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {

        Auth::user();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }
    
    public function editProfile()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.content.authentications.edit-admin-profile', compact('user'));
        } else {
            $driverDetail = $user->driverDetail;
            return view('driver.profile.edit', compact('user', 'driverDetail'));
        }
    }


    public function UpdateAdmindetail(Request $request)
    {
       
        $userid = Auth::user()->id;
        $user = User::findOrFail($userid);

        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'currentPassword' => $request->newPassword ? 'required' : 'nullable',
            'newPassword' => 'nullable|min:6',
            'confirmPassword' => 'nullable|same:newPassword',
        ]);

        if ($request->newPassword && !Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'extra' => 'The current password is incorrect.',
            ]);
        }

        
        $user->name = $request->name;
        $user->email = $request->email;

        
        if ($request->newPassword) {
            $user->password = Hash::make($request->newPassword);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Details updated successfully',
        ]);
    }
}
