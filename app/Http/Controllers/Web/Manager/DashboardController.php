<?php

namespace App\Http\Controllers\Web\Manager;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends WebController
{
    public function index(): View
    {
        return $this->placeholder('Manager Dashboard', 'Manager', 'Tong quan van hanh chi nhanh.');
    }

    public function filter(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly loc dashboard manager.');
    }
}
