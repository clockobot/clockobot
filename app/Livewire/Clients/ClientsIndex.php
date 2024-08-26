<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ClientsIndex extends Component
{
    use WithPagination;

    public string $query = '';

    protected $listeners = [
        'refreshClientsList' => '$refresh',
    ];

    #[Computed]
    public function clients()
    {
        if (! empty($this->query)) {
            $results = Client::where('name', 'like', '%'.$this->query.'%')
                ->orWhere('contact_name', 'like', '%'.$this->query.'%')
                ->orWhere('contact_email', 'like', '%'.$this->query.'%')
                ->orderBy('name', 'asc')->paginate(15);

            return $results;
        } else {
            $results = Client::orderBy('name', 'asc')->paginate(15);

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
        return view('livewire.clients.clients-index');
    }
}
