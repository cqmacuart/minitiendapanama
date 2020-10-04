<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Customer;
use App\Order;
use App\OrderDetail;
use App\OrderState;

class OrdersController extends Controller
{

    public $customerEmail;
    public $customerName;

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
        $query = DB::table('vw_orders');
        $orders = $query->orderBy('id', 'ASC')
            ->get();
        return response()->json($orders, 200);
    }

    public function filter($filtro)
    {
        //
        $query = DB::table('vw_orders');
        $orders = $query->orderBy('id', 'ASC')
            ->where("status", $filtro)
            ->orWhere("pedido", "LIKE", '%' . $filtro)
            ->get();
        return response()->json($orders, 200);
    }

    public function info()
    {
        //
        $orders = Order::count();
        $actives = Order::where('orderstate_id', 5);
        $inactives = Order::where('orderstate_id', '<', 5);
        return response()->json([
            'total' => $orders,
            'activas' => $actives->count(),
            'inactivas' => $inactives->count()
        ], 200);
    }



    public function customerByOrder($id)
    {
        $customer = \App\Customer::where('id', $id)->get();
        return response()->json($customer, 200);
    }

    public function pedido($token)
    {
        $order = \App\Order::where("serialize", $token)->addSelect(['estado' => OrderState::select('descripcion')->whereColumn('id', 'orders.orderstate_id')])->get();
        if (sizeof($order) > 0) {
            $details = \App\OrderDetail::where("order_id", $order[0]->id)->get();
            $customer = \App\Customer::where('order_pedido', $order[0]->pedido)->get();
            return response()->json(["order" => $order, "details" => $details, "customer" => $customer], 200);
        } else {
            return response()->json("Not Found", 204);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        // dd($request->all());
        $count = Order::all();
        $sequence = str_pad(count($count) + 1, 10, '0', STR_PAD_LEFT);
        $pedido = 'ORD' . $sequence;
        $customer = $request->all();
        $customer['order_pedido'] = $pedido;
        $customer["notificacion"] = $customer["notificacion"] ? 1 : 0;
        Customer::create($customer);
        $ordercustomer = Customer::where('order_pedido', $pedido)->get();
        $unique = uniqid();
        $order = [
            "pedido" => $pedido,
            "customer_id" => $ordercustomer[0]->id,
            "orderstate_id" => 1,
            "amount" => \Cart::getTotal(),
            "link" => url('/mipedido') . '/' . $unique,
            "serialize" => $unique
        ];
        // var_dump($order["pedido"]);
        // var_dump($customer["notificacion"]);

        Order::create($order);
        $orderid = Order::where('pedido', $pedido)->get();

        $cartItems = \Cart::getContent();
        // dd($cartItems);
        foreach ($cartItems as $key => $item) {
            $details["order_id"] = $orderid[0]->id;
            $details["order_pedido"] = $pedido;
            $details["product_id"] = $item->id;
            $details["quantity"] = $item->quantity;
            $details["unit_price"] = $item->price;
            $details["total_price"] = \Cart::get($item->id)->getPriceSum();
            // dd($details);
            OrderDetail::create($details);
        }

        \Cart::clear();
        // \Cart::session(auth()->id())->clear();
        return response()->json($order, 200);
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
        $order = Order::findOrFail($id);
        $details = OrderDetail::where("order_id", $id)->get();
        $customer = Customer::where('order_pedido', $order->pedido)->get();
        $setting = \App\Setting::findOrFail(1);
        return view('admin.orders.detail', compact("order", "details", "setting", "customer"));
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
        $orden = Order::findOrFail($id);
        $orden->orderstate_id = $request->estado;
        $orden->update();

        // enviar mensaje si la orden tiene notificación activa
        $customer = Customer::where("order_pedido", $orden->pedido)->get();
        if ($customer[0]->notificacion) { //si está activo, enviar notificacion
            $this->customerEmail = $customer[0]->email;
            $this->customerName = $customer[0]->nombre;
            $info = [
                "pedido" => $orden->pedido,
                "enlace" => $orden->link,
            ];
            $datos = [
                "titulo" => "Su pedido ha cambiado de estado.",
                "contenido" => $info
            ];
            Mail::send(
                "emails.estado",
                $datos,
                function ($mensaje) {
                    $mensaje->to($this->customerEmail, $this->customerName)->subject("ESTADO DE SU PEDIDO !!!");
                }
            );
        }
        return response()->json($orden, 200);
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
        $orders = Order::findOrFail($id);
        OrderDetail::where("order_id", $id)->delete();
        Customer::where('order_pedido', $orders->pedido)->delete();
        $orders->delete();
        return response()->json($orders, 200);
        // return redirect('/admin/categories');
    }
}
