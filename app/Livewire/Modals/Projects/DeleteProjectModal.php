<?php

namespace App\Livewire\Modals\Projects;

use App\Models\Project;
use LivewireUI\Modal\ModalComponent;

class DeleteProjectModal extends ModalComponent
{
    public Project $project_to_delete;

    public function mount($id)
    {
        $this->project_to_delete = Project::find($id);
    }

    public function confirmDelete()
    {
        $this->project_to_delete->delete();

        $this->dispatch('refreshProjectsList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.project_delete_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.projects.delete-project-modal');
    }
}
