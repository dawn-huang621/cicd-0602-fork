<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;

class ProductController extends Controller
{
    function index()
    {
        $result = DB::table('products')->get();
        return view('product.list', ['products' => $result]);
    }

    function create()
    {
        return view('product.new');
    }
    
    function store(Request $request)
    {
        try{
            // 驗證
            Validator::make(
                $request->all(), 
                [
                
                    'name' => 'required|string|unique|max:30',
                    'price' => 'required|string',
                    'name' => 'required|text'
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
                return view('product.index');
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
