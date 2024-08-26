<?php

namespace App\Livewire\WorkTypes;

use App\Models\WorkType;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class WorkTypesIndex extends ModalComponent
{
    use WithPagination;

    public string $query = '';

    public string $work_type_title = '';

    protected $listeners = [
        'refreshWorkTypesList' => 'refreshWorkTypesList',
    ];

    #[Computed]
    public function work_types()
    {
        if (! empty($this->query)) {
            return WorkType::where('title', 'like', '%'.$this->query.'%')
                ->orderBy('title', 'asc')->paginate(15);
        } else {
            return WorkType::orderBy('title', 'asc')->paginate(15);
        }
    }

    public function rules()
    {
        return [
            'work_type_title' => 'required|string|max:120',
        ];
    }

    public function messages()
    {
        return [
            'work_type_title.required' => 'Renseignez un titre pour ajouter une tâche.',
            'work_type_title.max' => 'Max. 120 caractères.',
        ];
    }

    public function addWorkType()
    {
        $this->validate();

        $work_type = new WorkType([
            'title' => $this->work_type_title,
        ]);

        $work_type->save();

        $this->work_type_title = '';

        $this->dispatch('$refresh');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.work_type_add_success')]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.work-types.work-types-index');
    }
}
