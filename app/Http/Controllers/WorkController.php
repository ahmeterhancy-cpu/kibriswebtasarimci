<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Work;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::ofType('work')->get();

        $works = Work::active()
            ->with('category')
            ->when($request->query('kategori'), function ($query, $slug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->get();

        return view('works.index', [
            'works' => $works,
            'categories' => $categories,
            'activeCategory' => $request->query('kategori'),
        ]);
    }

    public function show(Work $work): View
    {
        abort_unless($work->is_active, 404);

        return view('works.show', [
            'work' => $work->load('category'),
            'next' => Work::active()->whereKeyNot($work->getKey())->first()
                ?? Work::active()->first(),
            'others' => Work::active()->whereKeyNot($work->getKey())->take(3)->get(),
        ]);
    }
}
