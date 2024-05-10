<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Gate;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\Response;


class productController 
{
   
    public function index()
    {
       Gate::authorize('view' , 'products');
       $product = Product::paginate();
       return ProductResource::collection($product);
    }

    public function store(StoreProductRequest $request)
    {
       
        // $product = Product::create([
        //         'title'=>$request->input('title'),
        //         'description'=>$request->input('description'),
        //         'image'=>env('APP_URL').'/'.$url,
        //         'price'=>$request->input('price'),
        //     ]);
            Gate::authorize('edit' , 'products');
            $product = Product::create($request->only('title','image','description','price'));
            return response($product , Response::HTTP_CREATED);
    }
    public function show( $id )
    {
       Gate::authorize('view' , 'products');
       return new ProductResource(Product::find($id)); 
    }

    public function update(Request $request , $id)
    {
        Gate::authorize('edit' , 'products');
        $product = Product::find($id);
        $product->update($request->only('title','image','description','price'));
        return response(new ProductResource($product) , Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
      Gate::authorize('edit' , 'products');
       Product::destroy($id);
       return response(null,Response::HTTP_NO_CONTENT);
    }
}
