<?php

namespace App\Livewire\Modals\TimeEntries;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class EditTimeEntryModal extends ModalComponent
{
    public Collection $clients;

    public $projects;

    public Collection $work_types;

    public TimeEntry $timeEntry;

    public $start_time;

    public $start_date;

    public $end_time;

    public $end_date;

    public $client_id;

    public $project_id;

    public $work_type_id;

    public $description;

    public $link;

    public bool $billable;

    public function rules()
    {
        return [
            'start_time' => 'required',
            'start_date' => 'required',
            'end_time' => 'required',
            'end_date' => 'required',
            'client_id' => 'required|integer',
            'project_id' => 'required|integer',
            'work_type_id' => 'required|integer',
            'description' => 'nullable',
            'link' => 'nullable|url',
            'billable' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'start_time.required' => __('validation.start_time_required'),
            'start_date.required' => __('validation.start_date_required'),
            'end_time.required' => __('validation.end_time_required'),
            'end_date.required' => __('validation.end_date_required'),
            'client_id.required' => __('validation.client_id_required'),
            'project_id.required' => __('validation.project_id_required'),
            'work_type_id.required' => __('validation.work_type_id_required'),
            'link.url' => __('validation.link_url'),
        ];
    }

    public function mount($id)
    {
        $this->timeEntry = TimeEntry::find($id);

        $this->clients = Client::orderBy('name', 'asc')->get();
        $this->work_types = WorkType::orderBy('title', 'asc')->get();

        $this->projects = ($this->timeEntry) ? Project::where('client_id', $this->timeEntry->client_id)->orderBy('title', 'asc')->get() : [];

        $this->start_time = $this->timeEntry->start->format('H:i');
        $this->start_date = $this->timeEntry->start->format('Y-m-d');

        $this->end_time = $this->timeEntry->end->format('H:i');
        $this->end_date = $this->timeEntry->end->format('Y-m-d');

        $this->client_id = $this->timeEntry->client_id;
        $this->project_id = $this->timeEntry->project_id;
        $this->work_type_id = $this->timeEntry->work_type_id;
        $this->description = $this->timeEntry->description;
        $this->link = $this->timeEntry->link;

        $this->billable = $this->timeEntry->billable;
    }

    public function updateProjectsList()
    {
        $this->projects = Project::where('client_id', $this->client_id)->orderBy('title', 'asc')->get();
    }

    public function editManualTimeEntry()
    {
        $this->validate();

        $this->timeEntry->update([
            'start' => Carbon::createFromFormat('Y-m-d H:i', $this->start_date.' '.$this->start_time),
            'end' => Carbon::createFromFormat('Y-m-d H:i', $this->end_date.' '.$this->end_time),
            'client_id' => $this->client_id,
            'project_id' => $this->project_id,
            'user_id' => Auth::id(),
            'work_type_id' => $this->work_type_id,
            'description' => $this->description,
            'link' => $this->link,
            'billable' => $this->billable,
        ]);

        $this->dispatch('refreshTimeEntriesList');
        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.time_entry_edit_success')]);

        $this->closeModal();

    }

    public function render()
    {
        return view('livewire.modals.time-entries.edit-time-entry-modal');
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '5xl';
    }

    public static function closeModalOnEscape(): bool
    {
        return true;
    }
}
