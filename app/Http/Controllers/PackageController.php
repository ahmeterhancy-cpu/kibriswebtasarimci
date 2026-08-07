<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use App\Models\Package;
use Illuminate\Contracts\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::active()->get();

        return view('packages', [
            // Tek ızgara: e-ticaret paketi ayrı bir bölüme sürgün edildiğinde
            // sayfanın çok altında kalıyor ve bulunamıyordu.
            'packages' => $packages,
            'hasEcommerce' => $packages->contains(fn ($p) => $p->is_ecommerce),
            'faqs' => FaqItem::active()->forPage('packages')->get(),
        ]);
    }
}
