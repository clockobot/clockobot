<?php

namespace App\Livewire\Modals\Users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;

class AddUserModal extends ModalComponent
{
    public $name;

    public $email;

    public $locale;

    public bool $is_admin = false;

    public function rules()
    {
        return [
            'name' => 'required|string|max:120',
            'email' => 'required|email',
            'locale' => 'required|in:en,fr,es,de',
            'is_admin' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.name_required'),
            'name.max' => __('validation.name_max'),
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_valid'),
            'locale.required' => __('validation.locale_required'),
            'locale.in' => __('validation.locale_in'),
            'is_admin.boolean' => __('validation.is_admin_boolean'),
        ];
    }

    public function addUser()
    {
        $this->validate();

        $user = new User([
            'name' => $this->name,
            'contact_name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_admin' => $this->is_admin,
            'password' => Hash::make(Str::random(8)), // temporary password
        ]);

        $user->save();

        $this->dispatch('refreshUsersList');
        $this->closeModal();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.user_add_success')]);
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => __('notifications.user_add_error')]);
        }

    }

    public function render()
    {
        return view('livewire.modals.users.add-user-modal');
    }
}
