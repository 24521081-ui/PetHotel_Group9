<?php

namespace App\Http\Controllers\Web\Manager;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends WebController
{
    public function index(): View
    {
        return $this->placeholder('Quan ly dich vu chi nhanh', 'Manager', 'Danh sach dich vu tai chi nhanh.');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly luu dich vu chi nhanh.');
    }
}
