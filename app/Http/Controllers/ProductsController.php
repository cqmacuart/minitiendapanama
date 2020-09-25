<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $products = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])->get();
        return $products;
    }

    public function pageIndex()
    {
        //
        $products = Product::addSelect(['categoryname' => Category::select('nombre')->whereColumn('category_id', 'categories.id')])->get();
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
        $products = Product::where('category_id', $id)->where('estado_id', 1)->orderBy('position', 'asc')->get();
        return $products;
    }

    public function filteredBySearch($filter)
    {
        //
        $products = Product::where(DB::raw("CONCAT('nombre','short_des','long_des')"), 'LIKE', '%' . $filter . '%')
            ->orWhere('nombre', 'LIKE', '%' . $filter . '%')
            ->orWhere('short_des', 'LIKE', '%' . $filter . '%')
            ->orWhere('long_des', 'LIKE', '%' . $filter . '%')
            ->where('estado_id', 1)
            ->orderBy('position', 'asc')
            ->get();
        return $products;
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
        if (Product::create($entrada)) {
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
        $producto->update($entrada);
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
        return response()->json($producto, 200);
    }
}
