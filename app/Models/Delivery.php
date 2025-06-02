<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function get_city($province_id, $city_id=null){
        $query_str = ($city_id!= null) ? "&id=".$city_id : "";
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
          ])->get('https://api.rajaongkir.com/starter/city?province='.$province_id.$query_str);
  
          return $response['rajaongkir']['results'];
    }

    public static function get_city_options($province_id){}

    public static function get_provinces($id=null){
      $query_str = ($id!= null) ? "?id=".$id : "";
      $response = Http::withHeaders([
        'key' => config('services.rajaongkir.key'),
      ])->get('https://api.rajaongkir.com/starter/province'.$query_str);
  
      return $response['rajaongkir']['results'];
    }

    public static function get_province_options(){
      $arr = [];
      $list = Delivery::get_provinces();
      foreach ($list as $key => $value) {
        $arr[$value['province_id']] = $value['province'];
      }
      return $arr;
    }

    public static function get_address($id){
        $delivery = Delivery::find($id);
        $address = $delivery->address.", ".$delivery->city_name.", ".$delivery->province_name.", ".$delivery->post_code;
        return $address;
    }
}
