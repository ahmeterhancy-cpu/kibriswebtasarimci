<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\FaqItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact', [
            'faqs' => FaqItem::active()->forPage('contact')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:60'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
            // Bal kabı: gerçek kullanıcı bu alanı görmez, botlar doldurur.
            'website' => ['prohibited'],
        ], [], [
            'name' => __('site.form.name'),
            'email' => __('site.form.email'),
            'message' => __('site.form.message'),
        ]);

        unset($data['website']);

        ContactSubmission::create($data + ['locale' => app()->getLocale()]);

        return back()->with('status', 'contact-sent');
    }
}
