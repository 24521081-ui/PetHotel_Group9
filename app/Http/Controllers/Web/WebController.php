<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

abstract class WebController extends Controller
{
    protected function placeholder(string $title, string $section, string $description): View
    {
        return view('pages.placeholder', compact('title', 'section', 'description'));
    }

    protected function redirectToLogin(
        string $message = 'Vui lòng đăng nhập để tiếp tục.',
        string $errorKey = 'email'
    ): RedirectResponse {
        return redirect()
            ->route('authentication.login')
            ->withErrors([$errorKey => $message]);
    }

    protected function redirectIfGuest(string $message = 'Vui lòng đăng nhập để tiếp tục.'): ?RedirectResponse
    {
        return Auth::check() ? null : $this->redirectToLogin($message);
    }
}
