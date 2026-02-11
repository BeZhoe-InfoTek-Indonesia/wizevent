<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileComponent extends Component
{
    use WithFileUploads;

    public $name;

    public $email;

    public $current_password;

    public $password;

    public $password_confirmation;

    public $avatar;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
        'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
    ];

    public function mount()
    {
        // Check if user can edit their own profile
        if (! auth()->user()->can('users.edit') && ! auth()->id()) {
            abort(403, 'You are not authorized to edit this profile.');
        }

        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('profile-updated', message: 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password
        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');

            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Clear password fields
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        $this->dispatch('password-updated', message: 'Password updated successfully.');
    }

    public function updateAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $user = Auth::user();

        try {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Process and store new avatar
            $filename = 'avatars/'.$user->id.'_'.time().'.jpg';

            // Resize and optimize image
            $image = Image::make($this->avatar);
            $image->fit(300, 300, function ($constraint) {
                $constraint->upsize();
            });
            $image->encode('jpg', 80);

            // Store the processed image
            Storage::disk('public')->put($filename, $image->getEncoded());

            // Update user avatar
            $user->update(['avatar' => $filename]);

            // Clear avatar input
            $this->avatar = null;

            $this->dispatch('avatar-updated', message: 'Avatar updated successfully.');

        } catch (\Exception $e) {
            $this->addError('avatar', 'Failed to update avatar. Please try again.');
        }
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        $this->dispatch('avatar-deleted', message: 'Avatar deleted successfully.');
    }

    public function render()
    {
        return view('livewire.profile.profile-component', [
            'user' => Auth::user(),
        ]);
    }
}
