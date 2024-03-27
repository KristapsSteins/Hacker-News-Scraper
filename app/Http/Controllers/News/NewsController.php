<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.view');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function fetchNewsData() {
        $page = request('page') ?? 1;
        $pageLength = request('per_page') ?? 10;
        
        $articles = Article::filtered()->paginate($pageLength, ['*'], 'page', $page);
        
        return response()->json($articles, 200);
    }

    public function delete($id) {
        $article = Article::find($id);
    
        if ($article) {
            $article->delete();
            return response()->json([
                'status' => true,
                'message' => 'News item deleted successfully'
            ], 200);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'News item not found'
        ], 404);
    }
}
