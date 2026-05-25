<?php

namespace App\Http\Controllers\Web\Ceo;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends WebController
{
    public function index(): View
    {
        return $this->placeholder('CEO Dashboard', 'CEO', 'Tong quan dieu hanh he thong.');
    }

    public function filter(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly loc dashboard CEO.');
    }
}
