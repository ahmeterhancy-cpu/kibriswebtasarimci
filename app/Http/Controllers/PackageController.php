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
            'showcase' => $packages->where('is_ecommerce', false)->values(),
            'ecommerce' => $packages->where('is_ecommerce', true)->values(),
            'faqs' => FaqItem::active()->forPage('packages')->get(),
        ]);
    }
}
