<?php

namespace App\Livewire\Modals\TimeEntries;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class DeleteTimeEntryModal extends ModalComponent
{
    public TimeEntry $time_entry_to_delete;

    public User $user;

    public function mount($id)
    {
        $this->user = User::find(Auth::id());
        $this->time_entry_to_delete = TimeEntry::find($id);
    }

    public function confirmDelete()
    {
        // Only way I found with sqlite
        TimeEntry::find($this->time_entry_to_delete->id)->delete();

        $this->dispatch('refreshTimeEntriesList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.time_entry_delete_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.time-entries.delete-time-entry-modal');
    }
}
