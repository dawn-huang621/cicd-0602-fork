<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function index()
    {
        $result = DB::table('products')->get();
        return view('product.index', ['products' => $result]);
    }

    function create()
    {
        return view('product.new');
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
                    'description' => 'required|string'
                ], 
                [
                    'name' => 'product.name',
                    'price' => 'product.price',
                    'description' => 'product.description'
                ]
            );
            if ($validator->fails()) {
                return redirect('product/create')
                        ->withErrors($validator)
                        ->withInput();
            }

            $result = Product::create([
                'name' => $request->name,
                'price' => $request->price,
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
