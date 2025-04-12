<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function index() {
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
                'message' => 'Invalid Data',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $images = [];
        foreach($request->file('product_image') as $file) {
            $image = $file->store('uploads', 'public');
            array_push($images, $image);
        }
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->float('price'),
            'stock_quantity' => $request->integer('stock_quantity'),
            'image_paths' => $images
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'Product Added',
            'data' => $product,
        ], 201);
    }

    public function show(string $id) {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'message' => 'Product Fetched',
                'data' => $product
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Product Not Found',
            'data' => $product
        ],404);
    }

    public function update(Request $request, string $id) {
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
                'message' => 'Invalid Inputs',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $product = Product::find($id);
        if ($product) {
            $images = [];
            foreach ($request->file('product_image') as $file) {
                $image = $file->store('uploads', 'public');
                array_push($images, $image);
            }
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->float('price'),
                'stock_quantity' => $request->integer('stock_quantity'),
                'image_paths' => $images
            ]);
            
            return response()->json([
                'status' => 200,
                'message' => 'Product Updated',
                'data' => $product
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Product Not Found',
            'data' => $product,
        ], 404);
    }

    public function destroy(string $id) {
        $product = Product::find($id);
        if ($product) {
            $images = $product->image_paths;
            Storage::disk('public')->delete($images);
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product Deleted',
                'data' => null,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Product Not Found',
            'data' => null,
        ], 404);
    }
}
