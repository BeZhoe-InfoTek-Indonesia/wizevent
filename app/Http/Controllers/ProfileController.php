<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Upload or update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Process and store new avatar
            $avatar = $request->file('avatar');
            $filename = 'avatars/'.$user->id.'_'.time().'.'.$avatar->getClientOriginalExtension();

            // Resize and optimize image
            $image = Image::read($avatar);
            $image->cover(300, 300);
            $encoded = $image->toJpeg(80);

            // Store the processed image
            Storage::disk('public')->put($filename, (string) $encoded);

            // Update user avatar
            $user->update(['avatar' => $filename]);

            return redirect()->route('profile.show')
                ->with('success', 'Avatar updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update avatar. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Avatar deleted successfully.');
    }

    /**
     * Show the user's activity dashboard.
     */
    public function activity(Request $request)
    {
        $user = $request->user();

        // Get recent login attempts (placeholder for future activity logging)
        $recentLogins = [];
        $profileChanges = [];

        return view('profile.activity', compact('user', 'recentLogins', 'profileChanges'));
    }

    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string'],
            'confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify password
        if (! Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'The password is incorrect.'])
                ->withInput();
        }

        // Prevent deletion of the last Super Admin
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = \App\Models\User::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                return redirect()->back()
                    ->with('error', 'Cannot delete the last Super Admin account.');
            }
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout user
        auth()->logout();

        // Delete user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Your account has been deleted successfully.');
    }

    /**
     * Show account deletion confirmation form.
     */
    public function deleteAccount(Request $request)
    {
        return view('profile.delete-account', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Download user's data as JSON file.
     */
    public function downloadData(Request $request)
    {
        $user = $request->user();

        // Prepare user data for download
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at->toIso8601String(),
                'updated_at' => $user->updated_at->toIso8601String(),
            ],
            'email_notifications' => $user->email_notifications ?? [],
            'in_app_notifications' => $user->in_app_notifications ?? [],
        ];

        // Create JSON file
        $filename = 'user_data_'.$user->id.'_'.time().'.json';
        $content = json_encode($data, JSON_PRETTY_PRINT);

        // Return file download
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }
}
