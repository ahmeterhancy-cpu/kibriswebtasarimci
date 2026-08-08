<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Package;
use App\Models\Sector;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        return view('locations.index', [
            'locations' => Location::active()->get()->groupBy('region'),
        ]);
    }

    public function show(Location $location): View
    {
        abort_unless($location->is_active, 404);

        return view('locations.show', [
            'location' => $location,
            // Şehir sayfası bilerek geneldir; ayrışan içerik sektörlerde.
            // Bu liste sayfanın asıl işlevi: ziyaretçiyi doğru sektöre taşımak.
            'sectors' => Sector::active()->get(),
            'services' => Service::active()->take(6)->get(),
            'packages' => Package::active()->projects()->take(3)->get(),
            // Aynı bölgedeki diğer şehirler — iç bağlantı ve gezinme için.
            'siblings' => Location::active()
                ->region($location->region)
                ->whereKeyNot($location->getKey())
                ->get(),
        ]);
    }
}
