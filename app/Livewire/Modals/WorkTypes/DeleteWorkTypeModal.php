<?php

namespace App\Livewire\Modals\WorkTypes;

use App\Models\WorkType;
use LivewireUI\Modal\ModalComponent;

class DeleteWorkTypeModal extends ModalComponent
{
    public WorkType $work_type_to_delete;

    public function mount($id)
    {
        $this->work_type_to_delete = WorkType::find($id);
    }

    public function confirmDelete()
    {
        WorkType::find($this->work_type_to_delete->id)->delete();

        $this->dispatch('refreshWorkTypesList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.work_type_delete_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.work-types.delete-work-type-modal');
    }
}
