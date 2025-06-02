<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public function transaction_detail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }
    
    public function replies(){
        return $this->hasMany(TestimonyReply::class);
    }

    public static function get_available(){
        return Testimonial::where('active',1)->get();
    }
}
