<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // If user exists but doesn't have google_id, update it
                if (! $user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }

                Auth::login($user);

                return redirect()->intended($this->getRedirectRoute($user));
            } else {
                // For new Google users, redirect to registration with pre-filled data
                session(['google_user' => [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]]);

                return redirect()->route('register');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login with Google. Please try again.');
        }
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute($user): string
    {
        if ($user->hasRole(['Super Admin', 'Event Manager', 'Finance Admin', 'Check-in Staff'])) {
            return route('filament.admin.pages.dashboard');
        }

        // Visitors go to events page
        return route('events.index');
    }
}
