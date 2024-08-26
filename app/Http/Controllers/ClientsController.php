<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

//use Livewire\WithPagination;

class ClientsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('clients.index');
    }
}
