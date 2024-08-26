<?php

namespace App\Livewire\Modals\Clients;

use App\Models\Client;
use LivewireUI\Modal\ModalComponent;

class DeleteClientModal extends ModalComponent
{
    public Client $client_to_delete;

    public function mount($id)
    {
        $this->client_to_delete = Client::find($id);
    }

    public function confirmDelete()
    {
        // Only way I found with sqlite
        Client::find($this->client_to_delete->id)->delete();

        $this->dispatch('refreshClientsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.client_delete_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.clients.delete-client-modal');
    }
}
