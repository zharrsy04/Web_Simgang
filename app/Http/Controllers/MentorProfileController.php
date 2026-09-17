<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MentorProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('mentor.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:tbl_login,username,' . $user->id,
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->pass = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('mentor.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
