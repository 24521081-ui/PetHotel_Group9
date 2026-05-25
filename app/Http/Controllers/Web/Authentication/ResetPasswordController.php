<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends WebController
{
    public function show(): View
    {
        return view('auth.reset-password');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly dat lai mat khau.');
    }
}
