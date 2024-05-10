<?php

namespace App\Http\Controllers\Infuencer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController 
{
   public function index( Request $request){
    $query = Product::query();
    if($s = $request->input('s')) {
        $query->whereRaw("title LIKE '%{$s}%'")->orWhereRaw("description LIKE '%{$s}%'");
    }
    return ProductResource::collection($query->get()) ;
   }
}
