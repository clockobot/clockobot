<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TimerTrigger extends Component
{
    public $startDate = null;

    public $disabledTimer = false;

    public $started = false;

    protected $listeners = [
        'startAutoTimer' => 'startAutoTimer',
        'refreshTimeTrigger' => 'refreshTimeTrigger',
        'timerStopped' => 'refreshTimeTrigger',
    ];

    #[Computed]
    public function timeEntry()
    {
        return TimeEntry::where('user_id', Auth::id())->whereNull('end')->first();
    }

    public function mount()
    {
        $timeEntry = $this->timeEntry();

        if ($timeEntry !== null) {
            $this->started = true;
            $this->startDate = $timeEntry->start;
        }

        // Disable the timer if there are no clients, projects, or work types
        $this->disabledTimer = Client::doesntExist() || Project::doesntExist() || WorkType::doesntExist();
    }

    public function refreshTimeTrigger()
    {
        $timeEntry = $this->timeEntry();

        if ($timeEntry !== null) {
            $this->started = true;
            $this->dispatch('runTimer');
        } else {
            $this->started = false;
        }

        // Disable the timer if there are no clients, projects, or work types
        $this->disabledTimer = Client::doesntExist() || Project::doesntExist() || WorkType::doesntExist();
    }

    public function startTimer()
    {
        if (is_null($this->timeEntry())) {
            TimeEntry::create([
                'user_id' => Auth::id(),
                'start' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $this->dispatch('refreshTimeTrigger');
    }

    public function startAutoTimer($client_id = null, $project_id = null, $work_type_id = null)
    {
        if (is_null($this->timeEntry())) {
            TimeEntry::create([
                'client_id' => $client_id,
                'project_id' => $project_id,
                'work_type_id' => $work_type_id,
                'user_id' => Auth::id(),
                'start' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $this->dispatch('refreshTimeTrigger');
    }

    public function render()
    {
        return view('livewire.timer-trigger');
    }
}
