<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Response;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return Response::json($products);
    }

    public function getProduct($id)
    {
        $product = Product::find($id);

        return Response::json($product);

        dd($product);

    
    }

    public function getProductImages($id)
    {
        $product = Product::find($id);
        //dump the product image
         dd($product->image);
        return json_decode($product->image);

    }


}
