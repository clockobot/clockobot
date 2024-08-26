<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageSettings extends Component
{
    public User $user;

    public $name;

    public $email;

    public $locale;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.name_required'),
            'name.max' => __('validation.name_max'),
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_valid'),
        ];
    }

    public function mount()
    {
        $this->user = User::find(Auth::id());
        $this->name = $this->user->name;
        $this->locale = $this->user->locale;
        $this->email = $this->user->email;
    }

    public function save()
    {
        $this->validate();

        $currentLocaleChanged = false;

        if ($this->locale === $this->user->locale) {
            $currentLocaleChanged = false;
        } else {
            $currentLocaleChanged = true;
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
        ]);

        if (!$currentLocaleChanged) {
            $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.settings_updated')]);
        } else {
            // Make sure to reload the page for the interface to show translated strings.
            return redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        return view('livewire.settings.manage-settings');
    }
}
