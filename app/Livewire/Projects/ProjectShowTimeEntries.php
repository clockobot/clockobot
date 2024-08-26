<?php

namespace App\Livewire\Projects;

use Livewire\Component;

class ProjectShowTimeEntries extends Component
{
    public $project;

    protected $listeners = [
        'refreshProjectTimeEntries' => '$refresh',
    ];

    public function mount($project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.projects.project-show-time-entries');
    }
}
