<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Product;
use App\Category;
use App\Tipo;
use App\Estado;

class ProductsController extends Controller
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
        $products = Product::orderBy('position')->orderBy('category_id')->addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])->get();
        return $products;
    }

    public function pageIndex()
    {
        //
        $products = Product::orderBy('position')
            ->orderBy('category_id')
            ->addSelect(
                ['categoryname' => Category::select('nombre')
                    ->whereColumn('category_id', 'categories.id')]
            )->get();
        return $products;
    }

    public function info()
    {
        //
        $products = Product::count();
        $actives = Product::where('estado_id', 1);
        $inactives = Product::where('estado_id', 2);
        return response()->json([
            'total' => $products,
            'activas' => $actives->count(),
            'inactivas' => $inactives->count()
        ], 200);
    }

    public function filteredByCategory($id)
    {
        //
        if ($id > 0) {
            $products = Product::orderBy('position', 'ASC')
                ->where('category_id', $id)
                ->where('estado_id', 1)
                ->get();
        } else {
            $firstEnableCat = Category::first()->where("estado_id", 1)->orderBy("position", "ASC")->get();
            $products = Product::where('products.estado_id', 1)
                ->where("category_id", $firstEnableCat[0]->id)
                ->orderBy('products.position', 'ASC')
                ->get();
        }

        return response()->json($products, 200);
    }

    public function filteredBySearch($filter)
    {
        //
        $products = Product::where(DB::raw("CONCAT('nombre','short_des','long_des')"), 'LIKE', '%' . $filter . '%')
            ->orWhere('nombre', 'LIKE', '%' . $filter . '%')
            ->orWhere('short_des', 'LIKE', '%' . $filter . '%')
            ->orWhere('long_des', 'LIKE', '%' . $filter . '%')
            ->where('estado_id', 1)
            ->orderBy('position', 'ASC')
            ->get();
        return response()->json($products, 200);
    }

    public function filtered($product, $category, $status)
    {
        $searchProduct = $product == 'null' ? NULL : $product;
        $searchCategory = $category == 'null' ? NULL : $category;
        $searchStatus = $status == 'null' ? NULL : $status;

        if (!$searchProduct && !$searchCategory && !$searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])->get();
            return $productos;
        }
        // ELEGIR SOLO PRODUCTO POR NOMBRE
        if ($searchProduct and !$searchCategory and !$searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('nombre', 'LIKE', '%' . $searchProduct . '%')
                ->orderBy('position', 'asc')
                ->get();
            // ELEGIR SOLO PRODUCTO POR NOMBRE Y CATEGORIA
        } else if ($searchProduct and $searchCategory and  !$searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('nombre', 'LIKE', '%' . $searchProduct . '%')
                ->where('category_id', $searchCategory)
                ->orderBy('position', 'asc')
                ->get();
            # code...
            // ELEGIR SOLO PRODUCTO POR NOMBRE Y ESTADO
        } else if ($searchProduct and  !$searchCategory and $searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('nombre', 'LIKE', '%' . $searchProduct . '%')
                ->where('estado_id', $searchStatus)
                ->orderBy('position', 'asc')
                ->get();
            // ELEGIR SOLO PRODUCTO POR NOMBRE ,CATEGORIA Y ESTADO
        } else if ($searchProduct and $searchCategory and $searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('nombre', 'LIKE', '%' . $searchProduct . '%')
                ->where('category_id', $searchCategory)
                ->where('estado_id', $searchStatus)
                ->orderBy('position', 'asc')
                ->get();
            // ELEGIR SOLO PRODUCTO POR ESTADO Y CATEGORIA
        } else if (!$searchProduct and $searchCategory and $searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('category_id', $searchCategory)
                ->where('estado_id', $searchStatus)
                ->orderBy('position', 'asc')
                ->get();
            // ELEGIR SOLO PRODUCTO POR ESTADO
        } else if (!$searchProduct and  !$searchCategory and $searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('estado_id', $searchStatus)
                ->orderBy('position', 'asc')
                ->get();
            // ELEGIR SOLO PRODUCTO POR CATEGORIA
        } else if (!$searchProduct and $searchCategory and  !$searchStatus) {
            $productos = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])
                ->where('category_id', $searchCategory)
                ->orderBy('position', 'asc')
                ->get();
        }
        return $productos;
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
        return view("admin.products.create", compact("optionst", "optionse", "optionsc", "setting"));
    }


    public function saveFileImage(Request $request)
    {
        // $file = $request->file;
        $random = strtotime("now");
        // $fileName = $file->getClientOriginalName();
        // $serverName = $random . '_' . $file->getClientOriginalName();

        if ($archivo = $request->file("file")) {
            $nombre = $random . '_' . $archivo->getClientOriginalName();
            $archivo->move('img/products', $nombre);
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
        //
        $entrada = $request->all();
        if ($entrada["quantity"] == 0) {
            $entrada["estado_id"] = 2;
        } else {
            $entrada["estado_id"] = 1;
        }

        $totalProducts = Product::count();
        $this->swapPosition($totalProducts + 1, $entrada["position"]);
        if (Product::create($entrada)) {
            // $this->builtFBCSV();
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
        //
        $product = Product::findOrFail($id);
        return response()->json($product, 200);
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
        $product = Product::findOrFail($id);
        return response()->json($product, 200);
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
        //
        $producto = Product::findOrFail($id);
        $entrada = $request->all();
        if ($entrada["estado_id"] == 1) {
            if ($entrada["quantity"] == 0) {
                $entrada["estado_id"] = 2;
            } else {
                $entrada["estado_id"] = 1;
            }
        }
        //swap position
        $this->swapPosition($producto->position, $entrada["position"]);
        $producto->update($entrada);
        // $this->builtFBCSV();
        return response()->json($producto, 200);
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
        $producto = Product::findOrFail($id);
        $producto->delete();
        // $this->builtFBCSV();

        //posición borrada
        $posicion = $producto->position;
        //cantidad a tomar productos
        $cantidad = Product::count();

        //contar productos desde posición eliminada
        $productos = Product::orderBy("position", "ASC")->offset($posicion - 1)->limit($cantidad)->get();
        foreach ($productos as $value) {
            Product::where('position', $value->position)->update(["position" => ($value->position - 1)]);
        }
        return response()->json($producto, 200);
    }

    public function swapPosition($lastPosition, $swapPosition)
    {
        //get Product with current position to swapp
        Product::where('position', $swapPosition)->update(["position" => $lastPosition]);
    }

    // public function builtFBCSV()
    // {
    //     $productos = Product::all();
    //     $ajustes = \App\Setting::first();
    //     //Añadir primera fila de encabezados al arreglo
    //     $encabezados = ["id", "title", "description", "availability", "inventory", "condition", "price", "link", "image_link", "brand"];
    //     $csvProductsArray[] = $encabezados;
    //     if ($productos) {
    //         //crear arreglo de información que irá al csv
    //         foreach ($productos as $value) {
    //             $disponible = $value->quantity > 0 ? "in stock" : "out of stock";
    //             $csvProductsArray[] = [$value->id, ucfirst(htmlentities($value->nombre)), ucfirst(htmlentities($value->short_des)), $disponible, $value->quantity, "new", $value->price . " " . $ajustes->currency, URL::to("/") . '/producto/' . $value->id . "/", URL::to("/") . "/img/products/" . rawurlencode($value->image), "no-brand"];
    //         }
    //         //ARCHIVO DELIMITADO POR PUNTO Y COMA (;)
    //         //variables básicas: Titulo del archivo para FB
    //         //crear directorio si no existe
    //         $docTitle = "files/fb_business/facebook_business_product_csv.csv";
    //         $ruta = $docTitle;
    //         $delimitador = ",";
    //         $encapsulador = '"';

    //         // Generar el archivo linea a linea
    //         $file_handle = fopen($ruta, "w");
    //         foreach ($csvProductsArray as $value) {
    //             fputcsv($file_handle, $value, $delimitador, $encapsulador);
    //         }
    //         rewind($file_handle);
    //         fclose($file_handle);
    //     }
    // }
}
