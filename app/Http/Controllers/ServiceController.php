<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::active()->get(),
            'packages' => Package::active()->projects()->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('services.show', [
            'service' => $service,
            'others' => Service::active()->whereKeyNot($service->getKey())->take(4)->get(),
            'works' => Work::active()->take(3)->get(),
        ]);
    }
}
