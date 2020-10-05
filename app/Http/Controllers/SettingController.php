<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Setting;

class SettingController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware("EsAdmin");
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $settings = Setting::findOrFail(1);

        return response()->json($settings, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function saveFileImage(Request $request)
    {
        // $file = $request->file;
        // $fileName = $file->getClientOriginalName();
        // $serverName = $random . '_' . $file->getClientOriginalName();

        if ($archivo = $request->file("file")) {
            $nombre = $archivo->getClientOriginalName();
            $archivo->move('img/settings', $nombre);
        }
        return $nombre;
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Setting::all();
        $setting = \App\Setting::findOrFail(1);
        return view('/admin/settings/edit', compact("setting", "setting"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $entrada = $request->all();
        $setting->update($entrada);
        $this->builtFBCSV();
        return response()->json($setting, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function builtFBCSV()
    {
        $productos = \App\Product::all();
        $ajustes = Setting::first();
        //Añadir primera fila de encabezados al arreglo
        $encabezados = ["id", "title", "description", "availability", "inventory", "condition", "price", "link", "image_link", "brand"];
        $csvProductsArray[] = $encabezados;
        if ($productos) {
            //crear arreglo de información que irá al csv
            foreach ($productos as $value) {
                $csvProductsArray[] = [$value->id, ucfirst(htmlentities($value->nombre)), ucfirst(htmlentities($value->short_des)), "in stock", 100, "new", $value->price . " " . $ajustes->currency, URL::to("/") . '/producto/' . $value->id . "/", URL::to("/") . "/img/products/" . rawurlencode($value->image), "no-brand"];
            }
            //ARCHIVO DELIMITADO POR PUNTO Y COMA (;)
            //variables básicas: Titulo del archivo para FB
            //crear directorio si no existe
            $docTitle = "files/fb_business/facebook_business_product_csv.csv";
            $ruta = $docTitle;
            $delimitador = ",";
            $encapsulador = '"';

            // Generar el archivo linea a linea
            $file_handle = fopen($ruta, "w");
            foreach ($csvProductsArray as $value) {
                fputcsv($file_handle, $value, $delimitador, $encapsulador);
            }
            rewind($file_handle);
            fclose($file_handle);
        }
    }
}
