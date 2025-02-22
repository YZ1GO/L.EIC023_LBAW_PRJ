<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\View\View;

use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        if (auth_user()) {
            return view('pages.profile');
        } else {
            return redirect('/login');
        }
    }

    public function update(Request $request)
    {
        $user = auth_user();

        $rules = [
            'username' => [
                'required',
                'string',
                'min:5',
                'max:15',
                'unique:users,username,' . $user->id,
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'name' => [
                'required',
                'string',
                'min:5',
                'max:30',
                'regex:/^[a-zA-Z0-9 .\'-]+$/',
            ],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($user->buyer) {
            $rules['nif'] = 'nullable|digits:9';
        }

        $request->validate($rules);

        $user->username = $request->input('username');
        $user->name = $request->input('name');
        
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists and is not the default one
            if ($user->profile_picture && $user->profile_picture !== 'images/profile_pictures/default-profile-picture.png' && File::exists(public_path($user->profile_picture))) {
                File::delete(public_path($user->profile_picture));
            }

            // Store the new profile picture
            $file = $request->file('profile_picture');
            $path = 'images/profile_pictures/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profile_pictures'), $path);
            $user->profile_picture = $path;
        }

        if ($user->buyer) {
            $user->buyer->nif = $request->input('nif');
            $user->buyer->save();
        }
        $user->save();
        return redirect()->route('profile')
            ->withSuccess('Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:25|confirmed',
        ]);

        $user = auth_user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors('Current password is incorrect');
        }

        if (Hash::check($request->input('new_password'), $user->password)) {
            return back()->withErrors('New password cannot be the same as the current password');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('profile')
            ->withSuccess('Password updated successfully!');
    }

    public function deactivateUser(Request $request)
    {
        $user = auth_user();

        // Delete the old profile picture if it exists and is not the default one
        if ($user->profile_picture && $user->profile_picture !== 'images/profile_pictures/default-profile-picture.png' && File::exists(public_path($user->profile_picture))) {
            File::delete(public_path($user->profile_picture));
        }

        // Set is_active to false to trigger the anonymization
        $user->is_active = false;
        $user->save();

        // Log the user out after deactivation
        auth()->logout();

        return redirect()->route('home')
            ->withSuccess('Your account has been deleted. Game over... for now!');
    }
}
