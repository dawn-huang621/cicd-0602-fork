<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStockMovement;
use Illuminate\Support\Facades\Auth;
use Log;
use Session;

class StockMovementController extends Controller
{
    public function index(){
        return view('stockMovement.index', []);
    }

    public function list(Request $request){
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $data = StockMovement::with('product')->whereBetween('created_at', [$startDate, $endDate])->get();

        $stockMovements = [];
        $startStockMovementNumber = 260720;
        $startProductNumber = 202509000;
        foreach($data as $key => $value){
            $temp = [
                'stockMovementID' => 'S' . ($startStockMovementNumber + $value->id),
                'productID' => 'P' . ($startProductNumber + $value->product->id),
                'productName' => $value->product->name,
                'classification' => 1,
                'unit' => '單位',
                'balance' => $value->balance,
                'safetyStockAmount' => '安全庫存',
                'type' => $value->type,
                'created_at' => $value->created_at,
            ];
            array_push($stockMovements, $temp);
        }

        return response()->json([
            'status' => 'success',
            'data' => $stockMovements
        ]);
    }

    public function create(){

        $products = Product::with('stock')->get();
        
        // $products = Product::with('stock')->all();
        // dd($products, $user);
        return view('stockMovement.new', [
                                            'products' => $products
                                        ]);
    }

    // public function store(Request $request){
    //     $userId = Auth::id();
    //     // 建立出庫入庫
    //     // $request->userId
    //     // 建立歷史紀錄
    // }
}
