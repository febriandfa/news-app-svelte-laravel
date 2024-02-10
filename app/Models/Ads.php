<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    public $table = 'ads';

    protected $fillable = ['title', 'subtitle'];

    public function ads_images()
    {
        return $this->hasMany(AdsImage::class, 'ads_id');
    }
}
