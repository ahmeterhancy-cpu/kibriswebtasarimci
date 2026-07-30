<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use App\Models\Package;
use App\Models\QuoteRequest;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(): View
    {
        return view('quote', [
            'packages' => Package::active()->get(),
            'services' => Service::active()->get(),
            'faqs' => FaqItem::active()->forPage('quote')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:190'],
            'project_type' => ['nullable', 'string', 'max:60'],
            'package' => ['nullable', 'string', 'max:120'],
            'extras' => ['nullable', 'array'],
            'extras.*' => ['string', 'max:120'],
            'budget' => ['nullable', 'string', 'max:60'],
            'timeline' => ['nullable', 'string', 'max:60'],
            'estimate_min' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'estimate_max' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'message' => ['nullable', 'string', 'max:5000'],
            'website' => ['prohibited'],
        ], [], [
            'name' => __('site.form.name'),
            'email' => __('site.form.email'),
        ]);

        unset($data['website']);

        QuoteRequest::create($data + ['locale' => app()->getLocale()]);

        return back()->with('status', 'quote-sent');
    }
}
