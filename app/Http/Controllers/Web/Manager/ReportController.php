<?php

namespace App\Http\Controllers\Web\Manager;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends WebController
{
    public function index(): View
    {
        return $this->placeholder('Bao cao doanh thu', 'Manager', 'Bao cao doanh thu chi nhanh.');
    }

    public function export(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly xuat bao cao manager.');
    }
}
