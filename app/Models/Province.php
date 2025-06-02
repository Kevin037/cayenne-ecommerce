<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cities(){
        return $this->hasMany(City::class);
    }

    public function store(){
        $cek_provinces = Province::all();
        if (count($cek_provinces) > 0) {
            foreach ($cek_provinces as $province) {
                $province->delete();
            }
        }
        foreach (Delivery::get_provinces() as $province) {
            $province = Province::create([
                'id' => $province['province_id'],
                'name' => $province['province'],
            ]);
        }
    }
}
