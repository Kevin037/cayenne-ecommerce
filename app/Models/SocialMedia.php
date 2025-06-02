<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'social_medias';

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public static function get_available(){
        return SocialMedia::where('active',1)->orderBy('ordering')->get();
    }
}
