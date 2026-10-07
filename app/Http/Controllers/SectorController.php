<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Package;
use App\Models\Sector;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class SectorController extends Controller
{
    public function index(): View
    {
        return view('sectors.index', [
            'sectors' => Sector::active()->get(),
        ]);
    }

    public function show(Sector $sector): View
    {
        abort_unless($sector->is_active, 404);

        return view('sectors.show', [
            'sector' => $sector,
            'others' => Sector::active()->whereKeyNot($sector->getKey())->get(),
            'packages' => Package::active()->projects()->get(),
            // Sektör × şehir çapraz bağlantısı: "bu işi nerelerde yapıyoruz".
            'locations' => Location::active()->get(),
            'services' => Service::active()->get(),
        ]);
    }
}
