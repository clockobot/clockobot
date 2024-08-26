<?php

namespace App\Livewire\Modals\Users;

use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class EditUserModal extends ModalComponent
{
    public User $user_to_edit;

    public $name;

    public $email;

    public $locale;

    public bool $is_admin = false;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'locale' => 'required|in:en,fr,es,de',
            'is_admin' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.name_required'),
            'name.string' => __('validation.name_string'),
            'name.max' => __('validation.name_max'),
            'email.required' => __('validation.email_required'),
            'email.string' => __('validation.email_string'),
            'email.email' => __('validation.email_valid'),
            'email.max' => __('validation.email_max'),
            'locale.required' => __('validation.locale_required'),
            'locale.in' => __('validation.locale_in'),
            'is_admin.boolean' => __('validation.is_admin_boolean'),
        ];
    }

    public function mount($id)
    {
        $this->user_to_edit = User::find($id);

        $this->name = $this->user_to_edit->name;
        $this->email = $this->user_to_edit->email;
        $this->locale = $this->user_to_edit->locale;
        $this->is_admin = $this->user_to_edit->is_admin;
    }

    public function save()
    {
        $this->validate();
        $this->user_to_edit->update([
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
            'is_admin' => $this->is_admin,
        ]);

        $this->dispatch('refreshUsersList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.user_edit_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.users.edit-user-modal');
    }
}
