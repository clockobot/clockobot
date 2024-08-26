<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index');
    }
}
