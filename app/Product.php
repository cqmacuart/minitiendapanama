<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        "nombre",
        "short_des",
        "long_des",
        "estado_id",
        "category_id",
        "position",
        "price",
        "image",
    ];
    public function category()
    {
        return $this->belongsTo("App\Category");
    }

    public function estado()
    {
        return $this->belongsTo("App\Estado");
    }
}
