<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;

class HotelController extends WebController
{
    public function index(): View
    {
        return view('client.rooms.dog');
    }

    public function dogs(): View
    {
        return view('client.rooms.dog');
    }

    public function cats(): View
    {
        return view('client.rooms.cat');
    }

    public function show(string $areaId): View
    {
        $view = $areaId === 'cats' || $areaId === 'cat' ? 'client.rooms.cat' : 'client.rooms.dog';

        return view($view, [
            'id' => $areaId,
            'areaId' => $areaId,
        ]);
    }
}
