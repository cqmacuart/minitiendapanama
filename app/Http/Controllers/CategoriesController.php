<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Tipo;
use App\Estado;
use App\Product;

class CategoriesController extends Controller
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
        $categories = Category::orderBy('position', 'ASC')->get();
        foreach ($categories as $category) {
            $optionsc[$category->id] = $category->nombre;
        }
        return $categories;
    }

    public function pageIndex()
    {
        //
        $categories = Category::where('estado_id', 1)->orderBy('position', 'ASC')->get();
        // $categories = Category::where('estado_id', 1)->orderBy('position', 'ASC')->get();
        foreach ($categories as $category) {
            $optionsc[$category->id] = $category->nombre;
        }
        return $categories;
    }


    public function info()
    {
        //
        $categories = Category::count();
        $actives = Category::where('estado_id', 1);
        $inactives = Category::where('estado_id', 2);
        return response()->json([
            'total' => $categories,
            'activas' => $actives->count(),
            'inactivas' => $inactives->count()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $setting = \App\Setting::findOrFail(1);
        $tipos = Tipo::all();
        foreach ($tipos as $tipo) {
            $optionst[$tipo->id] = $tipo->nombre;
        }
        $estados = Estado::all();
        foreach ($estados as $estado) {
            $optionse[$estado->id] = $estado->nombre;
        }

        $categories = Category::all();
        foreach ($categories as $category) {
            $optionsc[$category->id] = $category->nombre;
        }
        return view("admin.categories.create", compact("optionst", "optionse", "optionsc", "setting"));
    }


    public function saveFileImage(Request $request)
    {
        // $file = $request->file;
        $random = strtotime("now");
        // $fileName = $file->getClientOriginalName();
        // $serverName = $random . '_' . $file->getClientOriginalName();

        if ($archivo = $request->file("file")) {
            $nombre = $random . '_' . $archivo->getClientOriginalName();
            $archivo->move('img/categories', $nombre);
        }
        return $nombre;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/admin');
        $entrada = $request->all();

        $totalCats = Category::count();
        $this->swapPosition($totalCats + 1, $entrada["position"]);
        if (Category::create($entrada)) {
            return response()->json($entrada, 200);
        } else {
            return response()->json($entrada, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json($category, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::findOrFail($id);

        return response()->json($category, 200);
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
        $category = Category::findOrFail($id);
        $entrada = $request->all();
        $this->swapPosition($category->position, $entrada["position"]);
        $category->update($entrada);
        return response()->json($category, 200);
    }

    public function swapPosition($lastPosition, $swapPosition)
    {
        //get Product with current position to swapp
        Category::where('position', $swapPosition)->update(["position" => $lastPosition]);
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
        Product::where("category_id", $id)->update(["category_id" => 1]);
        $category = Category::findOrFail($id);
        $category->delete();


        //posición borrada
        $posicion = $category->position;
        //cantidad a tomar productos
        $cantidad = Category::count();

        //contar productos desde posición eliminada
        $categorias = Category::orderBy("position", "ASC")->offset($posicion - 1)->limit($cantidad)->get();
        foreach ($categorias as $value) {
            Category::where('position', $value->position)->update(["position" => ($value->position - 1)]);
        }
        return response()->json($category, 200);
    }
}
