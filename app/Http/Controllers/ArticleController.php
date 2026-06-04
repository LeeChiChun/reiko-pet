<?php
namespace App\Http\Controllers;

use App\Models\{Article, ArticleBookmark};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::whereNotNull('published_at')->orderByDesc('published_at');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $articles   = $query->paginate(9)->withQueryString();
        $categories = Article::whereNotNull('published_at')->distinct()->pluck('category');
        return view('articles.index', compact('articles', 'categories'));
    }

    public function show(Article $article)
    {
        $related = Article::whereNotNull('published_at')
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->take(3)->get();

        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = ArticleBookmark::where('user_id', Auth::id())
                ->where('article_id', $article->id)
                ->exists();
        }

        return view('articles.show', compact('article', 'related', 'isBookmarked'));
    }

    public function toggleBookmark(Request $request, Article $article)
    {
        abort_if(!Auth::check(), 401);

        $existing = ArticleBookmark::where('user_id', Auth::id())
            ->where('article_id', $article->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
            $message    = '已取消收藏';
        } else {
            ArticleBookmark::create(['user_id' => Auth::id(), 'article_id' => $article->id]);
            $bookmarked = true;
            $message    = '已加入收藏';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['bookmarked' => $bookmarked, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
