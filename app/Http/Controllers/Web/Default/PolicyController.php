<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;

class PolicyController extends WebController
{
    public function index(): View
    {
        return view('client.policies.index');
    }

    public function show(string $policyId): View
    {
        return view('client.policies.index', [
            'id' => $policyId,
            'policyId' => $policyId,
        ]);
    }
}
