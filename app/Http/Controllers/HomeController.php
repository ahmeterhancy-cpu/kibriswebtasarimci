<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\FaqItem;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Work;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'services' => Service::active()->get(),
            'works' => Work::active()->featured()->with('category')->take(8)->get(),
            'brands' => Brand::active()->get(),
            'packages' => Package::active()->where('is_ecommerce', false)->take(3)->get(),
            'testimonials' => Testimonial::active()->take(6)->get(),
            'posts' => BlogPost::published()->with('category')->take(3)->get(),
            'faqs' => FaqItem::active()->forPage('home')->get(),
        ]);
    }
}
