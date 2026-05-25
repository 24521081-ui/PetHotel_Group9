<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;

class ServiceController extends WebController
{
    public function index(): View
    {
        return view('client.services.grooming');
    }

    public function spa(): View
    {
        return view('client.services.grooming');
    }

    public function grooming(): View
    {
        return view('client.services.grooming');
    }

    public function show(string $serviceId): View
    {
        return view('client.services.grooming', [
            'id' => $serviceId,
            'serviceId' => $serviceId,
        ]);
    }
}
