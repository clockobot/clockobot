<?php

namespace App\Livewire\Modals\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class DeleteUserModal extends ModalComponent
{
    public User $user_to_delete;

    public function mount($id)
    {
        $this->user_to_delete = User::find($id);
    }

    public function confirmDelete()
    {
        // Check if user is trying to delete his own account
        if (Auth::id() === $this->user_to_delete->id) {
            $this->dispatch('notify', ['type' => 'error', 'message' => __('notifications.user_delete_error_own_account')]);

            return;
        }

        $this->user_to_delete->delete();

        $this->dispatch('refreshUsersList');
        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.user_delete_success')]);
    }

    public function render()
    {
        return view('livewire.modals.users.delete-user-modal');
    }
}
