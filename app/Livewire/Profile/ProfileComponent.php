<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Services\ProfileService;

#[Layout('layouts.app-visitor')]
class ProfileComponent extends Component
{
    use WithFileUploads;

    public $name;

    public $email;

    public $identity_number;

    public $mobile_phone_number;

    public $current_password;

    public $password;

    public $password_confirmation;

    public $avatar;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'identity_number' => 'nullable|string|max:20',
        'mobile_phone_number' => 'nullable|string|max:20',
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
        $this->identity_number = $user->identity_number;
        $this->mobile_phone_number = $user->mobile_phone_number;
    }

    public function updateProfile(ProfileService $profileService)
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'identity_number' => 'nullable|string|max:20',
            'mobile_phone_number' => 'nullable|string|max:20',
        ]);

        $profileService->updateProfile($user, [
            'name' => $this->name,
            'email' => $this->email,
            'identity_number' => $this->identity_number,
            'mobile_phone_number' => $this->mobile_phone_number,
        ]);

        LivewireAlert::title(__('profile.profile_updated_successfully'))
            ->success()
            ->toast()
            ->position('top-end')
            ->timer(3000)
            ->show();
    }

    public function updatePassword(ProfileService $profileService)
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', __('profile.current_password_incorrect'));

            return;
        }

        $profileService->updatePassword($user, $this->password);

        // Clear password fields
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        LivewireAlert::title(__('profile.password_updated_successfully'))
            ->success()
            ->toast()
            ->position('top-end')
            ->timer(3000)
            ->show();
    }

    public function updateAvatar(ProfileService $profileService)
    {
        $this->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $user = Auth::user();

        try {
            $profileService->updateAvatar($user, $this->avatar);

            // Clear avatar input
            $this->avatar = null;

            LivewireAlert::title(__('profile.avatar_updated_successfully'))
                ->success()
                ->toast()
                ->position('top-end')
                ->timer(3000)
                ->show();

            $this->dispatch('avatar-updated');

        } catch (\Exception $e) {
            $this->addError('avatar', __('profile.failed_to_update_avatar'));
        }
    }

    public function deleteAvatar(ProfileService $profileService)
    {
        $user = Auth::user();
        $profileService->deleteAvatar($user);

        LivewireAlert::title(__('profile.avatar_deleted_successfully'))
            ->success()
            ->toast()
            ->position('top-end')
            ->timer(3000)
            ->show();

        $this->dispatch('avatar-deleted');
    }

    public function render(ProfileService $profileService)
    {
        $user = Auth::user();
        $dashboardData = $profileService->getDashboardData($user);

        return view('livewire.profile.profile-component', array_merge([
            'user' => $user,
        ], $dashboardData));
    }
}
