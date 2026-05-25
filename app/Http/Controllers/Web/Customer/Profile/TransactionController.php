<?php

namespace App\Http\Controllers\Web\Customer\Profile;

use App\Http\Controllers\Web\WebController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends WebController
{
    public function index(): RedirectResponse
    {
        return redirect()->route('profile.history-booking.index');
    }

    public function filter(Request $request): RedirectResponse
    {
        return back()->with('status', 'Xu ly loc lich su giao dich.');
    }
}
