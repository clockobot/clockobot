<?php

namespace App\Livewire\Reporting;

use App\Exports\ReportExport;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportingIndex extends Component
{
    public Collection $clients;

    public Collection $projects;

    public Collection $users;

    public $client_id;

    public $project_id;

    public $user_id;

    public $time_entries;

    public $start_date;

    public $start_time;

    public $end_date;

    public $end_time;

    public $hours_total;

    protected $listeners = [
        'refreshTimeEntriesOnReportingIndex' => '$refresh',
    ];

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
    public function users()
    {
        return User::orderBy('name', 'asc')->get();
    }

    #[Computed]
    public function time_entries()
    {
        $query = TimeEntry::query();

        if (! is_null($this->client_id) && ! empty($this->client_id)) {
            $query->where('client_id', intval($this->client_id));
        } else {
            $query->whereNotNull('client_id');
        }

        if (! is_null($this->project_id) && ! empty($this->project_id)) {
            $query->where('project_id', intval($this->project_id));
        }

        if (! is_null($this->user_id) && ! empty($this->user_id)) {
            $query->where('user_id', intval($this->user_id));
        }

        if ($this->start_date && $this->start_time) {
            $compiled_start_date = Carbon::createFromFormat('Y-m-d H:i', $this->start_date.' '.$this->start_time)->format('Y-m-d H:i:s');
            $query->where('start', '>=', $compiled_start_date);
        }

        if ($this->end_date && $this->end_time) {
            $compiled_end_date = Carbon::createFromFormat('Y-m-d H:i', $this->end_date.' '.$this->end_time)->format('Y-m-d H:i:s');
            $query->where('end', '<=', $compiled_end_date);
        }

        return $query->orderBy('start', 'desc')->get();
    }

    #[Computed]
    public function hours_total()
    {
        return process_hours_total($this->time_entries());
    }

    public function mount()
    {
        $this->clients = $this->clients();
        $this->users = $this->users();

        $this->start_date = now()->subWeek()->format('Y-m-d');
        $this->start_time = '00:00';
        $this->end_date = now()->format('Y-m-d');
        $this->end_time = '23:59';

        $this->time_entries = $this->time_entries();
        $this->projects = $this->projects();
    }

    public function resetData()
    {
        $this->redirect(request()->header('Referer'));
    }

    public function export()
    {
        // https://docs.laravel-excel.com/3.1/getting-started/
        return Excel::download(new ReportExport($this->time_entries()), 'report.xlsx');
    }

    public function render()
    {
        return view('livewire.reporting.reporting-index');
    }
}
