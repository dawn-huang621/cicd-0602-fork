<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Log;

class OrderController extends Controller
{
    function index()
    {
        $result = Order::All();
        $orders = Order::with('orderItems.product')->get();
        $totalMoney = 0;
        foreach($orders as $order){
            $order->order_number = 'O'. $order->order_number;
            $order->customer_name = $order->customer->name; // 取得關聯的客戶名稱
            $statusList = [0 => '草稿', 1 => '待審核', 2=> '已核准', 3=> '審核不通過', 4=> '已出貨', 5 => '已完成'];
            $statusStyle = [1=> 'status-pending', 2=> 'status-approved', 4=> 'status-shipped', 5=> 'status-completed', 3 => 'status-rejected',  '0' => 'status-draft'];
            $order->statusWord = $statusList[$order->status];
            // dd($order, $statusStyle);
            $order->statusStyle = $statusStyle[$order->status];
            foreach($order->orderItems as $item){
                $totalMoney += (int)$item->amount *(int)$item->price;
                $order->price += (int)$item->amount *(int)$item->price;
            }
            // dd($order, $totalMoney);
        }
        return view('order.index', [
                                        'orders' => $orders,
                                        'totalMoney' => $totalMoney
                                    ]);
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
    
    function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $orderService->create($request->validated());

        return redirect()->route('order.index')
            ->with('status', 'Order created successfully.');
    }

    function show(Request $request){
        // dd($id);
        $orderId = $request->input('orderId');

        // 品名，訂單數量，現有庫存
        $order = Order::with('customer')->findOrFail($id);
        $customer = $order->customer;
        $orderItems = OrderItem::with('product')->where('order_id', $id)->get();
        return view('order.show', [
                                    'order' => $order,
                                    'customer' => $customer,
                                    'orderItems' => $orderItems,
                                ]);
    }

    function showDetail(Request $request){
        // try{
            $orderId = $request->input('orderId');
            $order = Order::with('orderItems.product')->findOrFail($orderId);
            return response()->json([
                'status' => 'success',
                'data' => $order
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
        
    }

    function approveOrder(Request $request, int $id, OrderService $orderService)
    {
        try {
            $orderService->approve($id, $request->user()?->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Order approved successfully',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => collect($exception->errors())->flatten()->first(),
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    function dispatchOrder(Request $request){
        $orderId = $request->input('orderId');
        
        $order = Order::find($orderId);
        if(!$order){
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->status = 3; // 已出貨
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order dispatched successfully'
        ]);
    }

    function fetchByDateRange(Request $request){
        try{

            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $orders = Order::with('customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            
            foreach($orders as $value){
                $value->order_number = 'O' . $value->order_number;
            }

            return response()->json([
                'status' => 'success',
                'message' => $orders
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Order dispatched successfully'
            ]);
        }

    }
}
