<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $table = 'comments';

    protected $fillable = ['user_id', 'news_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function comment_replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id')->with('user');
    }
}
