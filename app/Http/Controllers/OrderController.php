<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    function index()
    {
        $result = DB::table('orders')->get();
        foreach($result as $order){
            $order->order_number = 'O'. $order->order_number;
        }
        return view('order.index', ['orders' => $result]);
    }

    function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        // dd($products);
        $productjs = [];
        foreach($products as $product){
            $productjs[$product->id] = $product;
        }
        // dd($productjs, json_encode($productjs));
        return view('order.new', [
                                    'products' => $products,
                                    'customers' => $customers,
                                    'productjs' => $productjs,
                                ]);
    }
    
    function store(StoreOrderRequest $request)
    {
        try{
            // 驗證
            $validated = $request->validated();
            $lastOrder = Order::latest()->first();

            $orderNumber = 134500001;
            if(isset($lastOrder)){
                $orderNumber = $lastOrder->order_number + 1;
                // $orderNumber = 'O' . $number;
            }
            
            // 建立order and order_item
            $query = DB::transaction(function () use ($request, $orderNumber) {
                // dd($request,$request->products , $orderNumber);
                // 建立訂單
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_id' => $request->customer,
                ]);
                if (!$order) throw new \Exception('建立訂單失敗');
                
                // 建立訂單品項
                $orderItemsData = [];
                foreach($request->products as $products){
                    $orderItemsData[] = [
                        'product_id' => $products['product_id'],
                        'order_id' => $order->id,
                        'amount' => $products['quantities'],
                        'created_at' => now(),  // insert() 不會自動補 timestamps
                        'updated_at' => now(),
                    ];
                }
                if(count($orderItemsData) == 0) throw new \Exception('品項不得為0');
                if (!OrderItem::insert($orderItemsData)) {
                    throw new \Exception('建立訂單品項失敗');
                }

                return true;
            }); 

            if($query){
                // 存入
                return redirect('order/index');
            } else {
                return redirect('order/create')
                        ->withErrors(['msg' => '建立訂單失敗'])
                        ->withInput();
            }

        } catch (ValidationException $exception) {
        return redirect('order/create')
            ->withErrors($exception->validator)
            ->withInput();
        } 
        // catch (\Exception $e) {
        //     return redirect('order/create')
        //         ->withErrors(['msg' => $e->getMessage()])
        //         ->withInput();
        // }
    }

    function show($id){
        // dd($id);
        
        $order = Order::with('customer')->findOrFail($id);
        $customer = $order->customer;
        $orderItems = OrderItem::with('product')->where('order_id', $id)->get();
        return view('order.show', [
                                    'order' => $order,
                                    'customer' => $customer,
                                    'orderItems' => $orderItems,
                                ]);
    }
}
