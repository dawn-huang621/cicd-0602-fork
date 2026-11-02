<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function list()
    {
        $products = Product::with('stock')->get();
        
        return view('products.index', compact('products'));
    }

    function create()
    {
        return view('products.new');
    }
    
    function store(Request $request)
    {
        try{
            // 驗證
            $validator = Validator::make(
                $request->all(), 
                [
                    'name' => 'required|string|unique:products,name|max:30',
                    'price' => 'required|string',
                    'amount' => 'required|int',
                    'description' => 'required|string'
                ], 
                [
                    'name' => 'product.name',
                    'price' => 'product.price',
                    'amount' => 'product.amount',
                    'description' => 'product.description'
                ]
            );
            if ($validator->fails()) {
                return redirect('product/create')
                        ->withErrors($validator)
                        ->withInput();
            }
// dd($request->amount);
            $result = Product::create([
                'name' => $request->name,
                'price' => (int)$request->price,
                'amount' => (int)$request->amount,
                'description' => $request->description,
            ]);
            if($result){
                // 存入
                return redirect('product/index');
            } else {
                return redirect('product/create')
                        ->withErrors($validator)
                        ->withInput();
            }

        } catch (ValidationException $exception) {
            $errorMessage =
                $exception->validator->getMessageBag()->getMessages();
        }
    }
}
