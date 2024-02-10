<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index() 
    {
        $news = News::all();

        return Inertia::render('News/IndexNews', [
            'newss' => $news,
        ]);
    }

    public function create() 
    {
        return Inertia::render('News/AddNews');
    }

    public function store(Request $request)
    {
        $image = $request->file('image');
        $imagePath = $image->storePubliclyAs('news/images', $image->getClientOriginalName(), 'public');

        $news = News::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $request->input('content'),
            'image' => $imagePath
        ]);

        return redirect()->route('news.index');
    }

    public function show($id)
    {
        $news = News::where('id', $id)->with(['comments'])->first();
        $comments = Comment::where('news_id', $id)->with(['news', 'user', 'comment_replies'])->get();
        $ads = Ads::with(['ads_images'])->latest()->take(3)->get();

        return Inertia::render('News/ShowNews', [
            'news' => $news,
            'comments' => $comments,
            'ads' => $ads
        ]);
    }

    public function edit($id)
    {
        $news = News::where('id', $id)->first();

        return Inertia::render('News/EditNews', [
            'news' => $news, 
        ]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::delete('news/images/' . basename($news->image));
            }

            $image = $request->file('image');
            $imagePath = $image->storePubliclyAs('news/images', $image->getClientOriginalName(), 'public');
        } else {
            $imagePath = $news->image;
        }

        $newsUpdate = $request->all();
        $newsUpdate['image'] = $imagePath;

        $news->update($newsUpdate);

        return redirect()->route('news.index');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if (Storage::exists('public/news/images/' . basename($news->image))) {
            Storage::delete('public/news/images/' . basename($news->image));
        }
        
        $news->delete();
    }

    public function storeComment(Request $request, $newsId)
    {
        $comments = Comment::create([
            'user_id' => auth()->user()->id,
            'news_id' => $newsId,
            'comment' => $request->input('comment'),
        ]);
    }

    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        $comment->delete();
    }

    public function storeCommentReply(Request $request, $newsId, $commentId)
    {
        $replies = CommentReply::create([
            'user_id' => auth()->user()->id,
            'news_id' => $newsId,
            'comment_id' => $commentId,
            'replies' => $request->input('replies')
        ]);
    }
}
