<?php

namespace App\Http\Controllers\Api;


use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(
            Product::latest()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'status'      => 'required|boolean',
           'medias'   => 'nullable|array',
'medias.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

        ]);
        

        $mediaPaths = [];

        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $mediaPaths[] = $file->store('products', 'public');
            }
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => $request->status,
            'medias'      => $mediaPaths,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'message' => 'Product show successfully',
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price'       => 'sometimes|numeric',
            'status'      => 'sometimes|boolean',
            'medias'   => 'nullable|array',
            'medias.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

        ]);

        $mediaPaths = $product->medias ?? [];

      
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $mediaPaths[] = $file->store('products', 'public');
            }
        }

        $product->update([
            'category_id' => $request->category_id ?? $product->category_id,
            'title'       => $request->title ?? $product->title,
            'description' => $request->description ?? $product->description,
            'price'       => $request->price ?? $product->price,
            'status'      => $request->status ?? $product->status,
            'medias'      => $mediaPaths,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
