<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public static function get_cities($id=null){
        $response = Http::withHeaders([
          'key' => config('services.rajaongkir.key'),
        ])->get('https://api.rajaongkir.com/starter/city');
    
        return $response['rajaongkir']['results'];
      }
}
