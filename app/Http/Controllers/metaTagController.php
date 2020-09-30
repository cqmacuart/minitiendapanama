<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MetaTagModel;
use App\Product;
use App\Setting;

class metaTagController extends Controller
{
    //
    static public function metaBuilder($route)
    {

        if (preg_match('/(\/producto\/)/', $route)) {
            // buscar el id del producto compartido
            // 1 . posición de la última barra de separación
            $lastBarPos = strrpos($route, '/');
            // 2 . extraer el ID del producto 
            $product_code = substr($route, $lastBarPos + 1);
            // 3 . Encontrar producto en cuestion por el ID
            $producto = Product::findOrFail($product_code);
            // ------------------------------------------------------

            // Obtener información del sitio
            $sitio = Setting::first();
            $info = ['product' => $producto, 'site' => $sitio];
            return ($info);
        } else {
            $sitio = Setting::first();
            $info = ['site' => $sitio];
            return ($info);
        }
    }
}
