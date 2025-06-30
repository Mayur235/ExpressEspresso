<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile page.
     */
    public function myProfile(Request $request): View
    {
        return view('profile.my_profile', [
            'user' => $request->user(),
            'title' => 'My Profile',
        ]);
    }

    /**
     * Show the edit profile form.
     */
    public function editProfileGet(Request $request): View
    {
        return view('profile.edit_profile', [
            'user' => $request->user(),
            'title' => 'Edit Profile',
        ]);
    }

    /**
     * Handle the edit profile form submission.
     */
    public function editProfilePost(Request $request, $id): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        if ($request->hasFile('image')) {
            if ($request->oldImage) {
                Storage::delete('public/' . $request->oldImage);
            }
            $validated['image'] = $request->file('image')->store('profile', 'public');
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.myProfile')->with('status', 'profile-updated');
    }

    /**
     * Show the change password form.
     */
    public function changePasswordGet(): View
    {
        return view('profile.change_password', [
            'title' => 'Change Password',
        ]);
    }

    /**
     * Handle the change password form submission.
     */
    public function changePasswordPost(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
