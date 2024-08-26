<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __invoke(Request $request): View
    {
        $users = User::orderBy('name', 'asc')->paginate(10);

        return view('users.index', compact('users'));
    }
}
