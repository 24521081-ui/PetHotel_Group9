<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;

class HomeController extends WebController
{
    public function index(): View
    {
        return view('client.home.index');
    }
}
