<?php

namespace App\Livewire\Modals\Projects;

use App\Models\Client;
use App\Models\Project;
use LivewireUI\Modal\ModalComponent;

class AddProjectModal extends ModalComponent
{
    public $clients;

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
            'description' => 'nullable|string',
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

    public function mount()
    {
        $this->clients = Client::orderBy('name')->get();
    }

    public function addProject()
    {
        $this->validate();

        $project = new Project([
            'title' => $this->title,
            'client_id' => $this->client_id,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'hour_estimate' => $this->hour_estimate,
        ]);

        $project->save();

        $this->dispatch('refreshProjectsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.project_add_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.projects.add-project-modal');
    }
}
