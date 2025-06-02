<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public function getMobilePhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public static function get_available(){
        return Banner::where('active','1')->get();
    }
}
