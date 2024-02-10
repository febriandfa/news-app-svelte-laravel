<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsImage extends Model
{
    use HasFactory;

    public $table = 'ads_image';

    protected $fillable = ['ads_id', 'images'];

    public function ads()
    {
        return $this->belongsTo(Ads::class, 'ads_id');
    }
}
