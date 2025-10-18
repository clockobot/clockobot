<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectsIndex extends Component
{
    use WithPagination;

    public string $query;

    protected $listeners = [
        'refreshProjectsList' => '$refresh',
    ];

    #[Computed]
    public function projects()
    {
        if (! empty($this->query)) {
            return Project::with('client')
                ->where('title', 'like', '%'.$this->query.'%')
                ->orWhereHas('client', function ($query) {
                    $query->where('name', 'like', '%'.$this->query.'%');
                })
                ->orderBy('title', 'asc')
                ->paginate(15);
        } else {
            return Project::with('client')
                ->orderBy('title', 'asc')
                ->paginate(15);
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.projects.projects-index');
    }
}
