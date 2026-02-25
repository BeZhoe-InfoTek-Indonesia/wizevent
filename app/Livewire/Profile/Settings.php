<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

/**
 * @property \Filament\Schemas\Schema $notificationsForm
 * @property \Filament\Schemas\Schema $privacyForm
 * @property \Filament\Schemas\Schema $localizationForm
 */
class Settings extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $notificationsData = [];

    public ?array $privacyData = [];

    public ?array $localizationData = [];

    public function mount(): void
    {
        $user = Auth::user();

        // Load existing preferences
        $emailNotifications = $user->email_notifications ?? User::getDefaultNotificationPreferences()['email_notifications'];
        $privacySettings = $user->privacy_settings ?? User::getDefaultPrivacySettings();

        // Populate public properties (used by wire:model in the view)
        $this->notificationsData = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'order_updates' => $emailNotifications['payment'] ?? true,
            'marketing_newsletter' => $emailNotifications['newsletter'] ?? ($emailNotifications['promotions'] ?? false),
        ];

        $this->privacyData = [
            'profile_visibility' => $privacySettings['profile_visibility'] ?? 'public',
            'show_email' => $privacySettings['show_email'] ?? false,
            'show_phone' => $privacySettings['show_phone'] ?? false,
        ];

        $this->localizationData = [
            'language' => session()->get('locale', config('app.locale')),
            'timezone' => 'Asia/Jakarta',
        ];

        // Also fill Filament forms (kept for any server-side schema validation)
        $this->notificationsForm->fill($this->notificationsData);
        $this->privacyForm->fill($this->privacyData);
        $this->localizationForm->fill($this->localizationData);
    }

    protected function getForms(): array
    {
        return [
            'notificationsForm',
            'privacyForm',
            'localizationForm',
        ];
    }

    public function notificationsForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('email_notifications')
                    ->label(__('profile.email_notifications'))
                    ->helperText(__('profile.email_notifications_desc'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),

                Toggle::make('sms_notifications')
                    ->label(__('profile.sms_notifications'))
                    ->helperText(__('profile.sms_notifications_desc'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),

                Toggle::make('order_updates')
                    ->label(__('profile.order_updates'))
                    ->helperText(__('profile.order_updates_desc'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),

                Toggle::make('marketing_newsletter')
                    ->label(__('profile.marketing_newsletter'))
                    ->helperText(__('profile.marketing_newsletter_desc'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),
            ])
            ->statePath('notificationsData');
    }

    public function privacyForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('profile_visibility')
                    ->label(__('profile.profile_visibility'))
                    ->options([
                        'public' => __('profile.public_visible'),
                        'private' => __('profile.private_hidden'),
                    ])
                    ->prefixIcon('heroicon-m-eye')
                    ->native(false),

                Toggle::make('show_email')
                    ->label(__('profile.show_email_publicly'))
                    ->helperText(__('profile.publicly_visible'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),

                Toggle::make('show_phone')
                    ->label(__('profile.show_phone_publicly'))
                    ->helperText(__('profile.publicly_visible'))
                    ->extraAttributes(['class' => 'settings-toggle-row']),
            ])
            ->statePath('privacyData');
    }

    public function localizationForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('language')
                    ->label(__('profile.preferred_language'))
                    ->options($this->getLanguagesProperty())
                    ->prefixIcon('heroicon-m-globe-alt')
                    ->native(false),
                Select::make('timezone')
                    ->label(__('profile.timezone'))
                    ->options($this->getTimezonesProperty())
                    ->prefixIcon('heroicon-m-clock')
                    ->native(false),
            ])
            ->statePath('localizationData');
    }

    public function discardChanges(): void
    {
        $this->mount();
        Toaster::info(__('profile.changes_discarded'));
    }

    public function save(): void
    {
        // Read directly from public properties (wire:model bindings in the view)
        $notifData = $this->notificationsData ?? [];
        $privacyData = $this->privacyData ?? [];
        $localData = $this->localizationData ?? [];

        $user = Auth::user();

        // 1. Save Notification Preferences
        $preferences = [
            'payment' => (bool) ($notifData['order_updates'] ?? true),
            'events' => true,
            'loved_events' => true,
            'promotions' => (bool) ($notifData['marketing_newsletter'] ?? false),
            'promotional_offers' => (bool) ($notifData['marketing_newsletter'] ?? false),
            'newsletter' => (bool) ($notifData['marketing_newsletter'] ?? false),
        ];

        $user->email_notifications = $preferences;
        $user->in_app_notifications = $preferences;

        // 2. Save Privacy Settings
        $user->privacy_settings = [
            'profile_visibility' => $privacyData['profile_visibility'] ?? 'public',
            'show_email' => (bool) ($privacyData['show_email'] ?? false),
            'show_phone' => (bool) ($privacyData['show_phone'] ?? false),
        ];

        // 3. Save Language (Session only)
        $newLocale = $localData['language'] ?? config('app.locale');
        $oldLocale = session()->get('locale', config('app.locale'));

        if ($newLocale !== $oldLocale) {
            session()->put('locale', $newLocale);
            app()->setLocale($newLocale);
        }

        $user->save();

        Toaster::success(__('profile.settings_updated_successfully'));

        if ($newLocale !== $oldLocale) {
            $this->redirect(request()->header('Referer'));
        }
    }

    public function downloadDataAction(): Action
    {
        return Action::make('downloadData')
            ->label(__('profile.download_my_data'))
            ->color('gray')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                $user = Auth::user();
                $data = [
                    'user' => $user->toArray(),
                    'settings' => [
                        'notifications' => $this->notificationsForm->getRawState(),
                        'privacy' => $this->privacyForm->getRawState(),
                        'localization' => $this->localizationForm->getRawState(),
                    ],
                ];
                $filename = 'user_data_'.$user->id.'_'.time().'.json';

                return response()->streamDownload(function () use ($data) {
                    echo json_encode($data, JSON_PRETTY_PRINT);
                }, $filename);
            });
    }

    public function deleteAccountAction(): Action
    {
        return Action::make('deleteAccount')
            ->label(__('profile.delete_my_account'))
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading(__('profile.delete_account'))
            ->modalDescription(__('profile.delete_account_desc'))
            ->form([
                TextInput::make('password')
                    ->password()
                    ->label(__('profile.password'))
                    ->required()
                    ->rule('current_password'),
            ])
            ->action(function (array $data) {
                $user = Auth::user();
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->delete();
                Auth::logout();

                return redirect()->route('home');
            });
    }

    public function getTimezonesProperty()
    {
        return [
            'Asia/Jakarta' => 'Asia/Jakarta (WIB)',
            'Asia/Makassar' => 'Asia/Makassar (WITA)',
            'Asia/Jayapura' => 'Asia/Jayapura (WIT)',
            'Asia/Singapore' => 'Asia/Singapore',
            'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
            'Asia/Bangkok' => 'Asia/Bangkok',
            'Asia/Hong_Kong' => 'Asia/Hong Kong',
            'Asia/Tokyo' => 'Asia/Tokyo',
            'Australia/Sydney' => 'Australia/Sydney',
            'Europe/London' => 'Europe/London',
            'Europe/Paris' => 'Europe/Paris',
            'America/New_York' => 'America/New York',
            'America/Los_Angeles' => 'America/Los Angeles',
        ];
    }

    public function getLanguagesProperty()
    {
        return [
            'en' => 'English',
            'id' => 'Indonesian',
        ];
    }

    public function render()
    {
        return view('livewire.profile.settings');
    }
}
