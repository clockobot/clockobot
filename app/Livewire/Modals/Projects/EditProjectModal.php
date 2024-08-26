<?php

namespace App\Livewire\Modals\Projects;

use App\Models\Client;
use App\Models\Project;
use LivewireUI\Modal\ModalComponent;

class EditProjectModal extends ModalComponent
{
    public $clients;

    public $project_to_edit;

    public $title;

    public $client_id;

    public $description;

    public $deadline;

    public $hour_estimate;

    public function rules()
    {
        return [
            'title' => 'required|string|max:120',
            'client_id' => 'required|integer',
            'description' => 'nullable',
            'deadline' => 'nullable|date',
            'hour_estimate' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.title_required'),
            'title.max' => __('validation.title_max'),
            'client_id.required' => __('validation.client_id_required'),
            'description.string' => __('validation.description_string'),
            'deadline.date' => __('validation.deadline_date'),
            'hour_estimate.numeric' => __('validation.hour_estimate_numeric'),
        ];
    }

    public function mount($id)
    {
        $this->clients = Client::orderBy('name', 'asc')->get();
        $this->project_to_edit = Project::find($id);
        $this->title = $this->project_to_edit->title;
        $this->client_id = $this->project_to_edit->client_id;
        $this->description = $this->project_to_edit->description;
        $this->deadline = $this->project_to_edit->deadline;
        $this->hour_estimate = $this->project_to_edit->hour_estimate;
    }

    public function save()
    {
        $this->validate();

        $this->project_to_edit->update([
            'title' => $this->title,
            'client_id' => $this->client_id,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'hour_estimate' => $this->hour_estimate,
        ]);

        $this->dispatch('refreshProjectsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.project_edit_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.projects.edit-project-modal');
    }
}
