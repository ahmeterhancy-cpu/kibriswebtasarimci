<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::ofType('blog')->get();

        $posts = BlogPost::published()
            ->with('category')
            ->when($request->query('kategori'), function ($query, $slug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'activeCategory' => $request->query('kategori'),
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('blog.show', [
            'post' => $post->load('category'),
            'related' => BlogPost::published()
                ->whereKeyNot($post->getKey())
                ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
                ->take(3)
                ->get(),
        ]);
    }
}
