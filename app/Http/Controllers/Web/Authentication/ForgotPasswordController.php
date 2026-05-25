<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends WebController
{
    public function show(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly gui email dat lai mat khau.');
    }
}
