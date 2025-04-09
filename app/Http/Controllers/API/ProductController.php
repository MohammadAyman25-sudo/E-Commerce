<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::paginate(15);
        return response()->json([
            "status" => 200,
            "message" => "Products Fetched",
            "data" => $products,
        ]);
    }

    public function store(Request $request) {
        $validator = validator($request->all(), [
            'name' => 'required',
            'price' => 'required|decimal:0,2',
            'description' => 'nullable',
            'stock_quantity' => 'numeric|nullable',
            'product_image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'message' => 'invalid data',
                'data' => $validator->errors()->all(),
            ]);
        }
        $product = Product::create($validator->validated());
        foreach($request->file('product_image') as $file) {
            $product->images()->create([
                'image_path' => $file->store('uploads', 'public'),
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'product added',
            'data' => $product,
        ]);
    }

    public function show(Request $request, string $id) {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'message' => 'product fetched',
                'data' => $product
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'product not found',
            'data' => $product
        ]);
    }

    public function update(Request $request, string $id) {
        $validator = validator($request->all(), [
            'name' => 'required',
            'price' => 'required|decimal:0,2',
            'description' => 'nullable',
            'stock_quantity' => 'numeric|nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => '401',
                'message' => 'invalid inputs',
                'data' => $validator->errors()->all(),
            ]);
        }
        $product = Product::find($id);
        if ($product) {
            $product->update($validator->validated());
            return response()->json([
                'status' => 200,
                'message' => 'product updated',
                'data' => $product
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'product not found',
            'data' => $product,
        ]);
    }

    public function destroy(Request $request, string $id) {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'product deleted',
                'data' => null,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'product not found',
            'data' => null,
        ]);
    }
}
