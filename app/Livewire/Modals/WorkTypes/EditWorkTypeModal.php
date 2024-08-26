<?php

namespace App\Livewire\Modals\WorkTypes;

use App\Models\WorkType;
use LivewireUI\Modal\ModalComponent;

class EditWorkTypeModal extends ModalComponent
{
    public WorkType $work_type_to_edit;

    public string $title;

    public function rules()
    {
        return [
            'title' => 'required|string|max:120',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.title_required'),
            'title.max' => __('validation.title_max'),
        ];
    }

    public function mount($id)
    {
        $this->work_type_to_edit = WorkType::find($id);
        $this->title = $this->work_type_to_edit->title;
    }

    public function save()
    {
        $this->validate();

        $this->work_type_to_edit->update([
            'title' => $this->title,
        ]);

        $this->dispatch('refreshWorkTypesList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.work_type_edit_success')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.work-types.edit-work-type-modal');
    }
}
