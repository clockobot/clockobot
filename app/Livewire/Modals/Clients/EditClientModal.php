<?php

namespace App\Livewire\Modals\Clients;

use App\Models\Client;
use LivewireUI\Modal\ModalComponent;

class EditClientModal extends ModalComponent
{
    public Client $client_to_edit;

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

    public function mount($id)
    {
        $this->client_to_edit = Client::find($id);

        $this->name = $this->client_to_edit->name;
        $this->contact_name = $this->client_to_edit->contact_name;
        $this->contact_email = $this->client_to_edit->contact_email;
        $this->contact_phone = $this->client_to_edit->contact_phone;
    }

    public function save()
    {
        $this->validate();
        $this->client_to_edit->update([
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
        ]);

        $this->dispatch('refreshClientsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.client_edit_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.clients.edit-client-modal');
    }
}
