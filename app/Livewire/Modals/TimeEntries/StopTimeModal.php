<?php

namespace App\Livewire\Modals\TimeEntries;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

class StopTimeModal extends ModalComponent
{
    public Collection $clients;

    public Collection $projects;

    public Collection $work_types;

    public $timeEntry;

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

    #[Computed]
    public function clients()
    {
        return Client::orderBy('name', 'asc')->get();
    }

    #[Computed]
    public function projects()
    {
        return Project::orderBy('title', 'asc')->where('client_id', $this->client_id)->get();
    }

    #[Computed]
    public function work_types()
    {
        return WorkType::orderBy('title', 'asc')->get();
    }

    #[Computed]
    public function timeEntry()
    {
        return TimeEntry::where('user_id', Auth::id())->whereNull('end')->first();
    }

    public function mount()
    {
        $this->clients = $this->clients();
        $this->projects = $this->projects();
        $this->work_types = $this->work_types();

        $this->timeEntry = $this->timeEntry();

        if (! is_null($this->timeEntry)) {
            $this->start_time = $this->timeEntry->start->format('H:i');
            $this->start_date = $this->timeEntry->start->format('Y-m-d');

            $this->end_time = now()->format('H:i');
            $this->end_date = now()->format('Y-m-d');

            $this->client_id = $this->timeEntry->client_id;
            $this->project_id = $this->timeEntry->project_id;
            $this->work_type_id = $this->timeEntry->work_type_id;
            $this->description = $this->timeEntry->description;
            $this->link = $this->timeEntry->link;

            $this->billable = $this->timeEntry->billable;
        } else {
            $this->closeModal();
            $this->redirect(request()->header('Referer')); // Need to keep this to not get any error if starting/opening on the other hand (desktop) - Todo: investigate
        }

    }

    public function addTimeEntry()
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

        $this->dispatch('refreshTimeTrigger');
        $this->dispatch('timerStopped'); // TimerTriggerEvent
        $this->dispatch('stopTimer');

        // Dispatch $refresh to other pages
        $this->dispatch('refreshHoursCountOnDashboard');
        $this->dispatch('refreshLastFiveEntriesOnDashboard');
        $this->dispatch('refreshLatestWorkOnDashboard');
        $this->dispatch('refreshGraphOnDashboard');
        $this->dispatch('refreshTimeEntriesList');
        $this->dispatch('refreshTimeEntriesOnReportingIndex');
        $this->dispatch('refreshProjectTimeEntries');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.time_entry_add_success')]);

        $this->closeModal();
    }

    public function deleteTimeEntry()
    {
        $this->timeEntry->delete();

        $this->dispatch('refreshTimeTrigger');
        $this->dispatch('timerStopped');
        $this->dispatch('stopTimer');

        $this->dispatch('notify', ['type' => 'warning', 'message' => __('notifications.time_entry_add_stopped')]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.time-entries.stop-time-modal');
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
