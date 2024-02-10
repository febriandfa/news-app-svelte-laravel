<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    use HasFactory;

    public $table = 'comment_replies';

    protected $fillable = ['user_id', 'news_id', 'comment_id', 'replies'];

    public function comments()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
