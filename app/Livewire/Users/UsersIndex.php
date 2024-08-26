<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public string $query = '';

    protected $listeners = [
        'refreshUsersList' => '$refresh',
    ];

    #[Computed]
    public function users()
    {
        if (! empty($this->query)) {
            $results = User::where('name', 'like', '%'.$this->query.'%')
                ->orWhere('email', 'like', '%'.$this->query.'%')
                ->orderBy('name', 'asc')->paginate(15);

            return $results;
        } else {
            $results = User::orderBy('name', 'asc')->paginate(15);

            return $results;
        }
    }

    public function updatedQuery()
    {
        $this->search();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.users.users-index');
    }
}
