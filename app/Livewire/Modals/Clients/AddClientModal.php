<?php

namespace App\Livewire\Modals\Clients;

use App\Models\Client;
use LivewireUI\Modal\ModalComponent;

class AddClientModal extends ModalComponent
{
    public $name;

    public $contact_name;

    public $contact_email;

    public $contact_phone;

    public function rules()
    {
        return [
            'name' => 'required|string|max:120',
            'contact_name' => 'nullable',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.name_required'),
            'name.max' => __('validation.name_max'),
            'contact_email.email' => __('validation.email_valid'),
        ];
    }

    public function addClient()
    {
        $this->validate();

        $client = new Client([
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
        ]);

        $client->save();

        $this->dispatch('refreshClientsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.client_add_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.clients.add-client-modal');
    }
}
