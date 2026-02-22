<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProfileService
{
    /**
     * Update user profile information.
     */
    public function updateProfile(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'identity_number' => $data['identity_number'] ?? $user->identity_number,
            'mobile_phone_number' => $data['mobile_phone_number'] ?? $user->mobile_phone_number,
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Process and update user avatar.
     */
    public function updateAvatar(User $user, $avatarFile): string
    {
        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Process and store new avatar
        $filename = 'avatars/' . $user->id . '_' . time() . '.jpg';

        // Resize and optimize image
        $image = Image::read($avatarFile);
        $image->cover(300, 300);
        $encoded = $image->toJpeg(80);

        // Store the processed image
        Storage::disk('public')->put($filename, (string) $encoded);

        // Update user avatar
        $user->update(['avatar' => $filename]);

        return $filename;
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }
    }

    /**
     * Get dashboard data for the user profile.
     */
    public function getDashboardData(User $user): array
    {
        return [
            'ticketsCount' => \App\Models\Ticket::whereHas('orderItem.order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'wishlistCount' => \App\Models\Favorite::where('user_id', $user->id)->count(),
            'historyCount' => \App\Models\Order::where('user_id', $user->id)->count(),
            'upcomingEvents' => \App\Models\Event::where('event_date', '>=', now())
                ->orderBy('event_date')
                ->take(5)
                ->get(),
            'featuredEvents' => \App\Models\Event::inRandomOrder()->take(4)->get(),
        ];
    }
}
