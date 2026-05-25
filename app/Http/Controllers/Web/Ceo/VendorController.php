<?php

namespace App\Http\Controllers\Web\Ceo;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VendorController extends WebController
{
    public function index(): View
    {
        return $this->placeholder('Doi tac va nha cung cap', 'CEO', 'Danh sach doi tac va nha cung cap.');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly luu doi tac va nha cung cap.');
    }
}
