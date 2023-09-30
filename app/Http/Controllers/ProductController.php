<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function index()
    {
        $products = $this->product->all();
        return view('product.index', compact('products'));
    }
    public function create()
    {
        return view('product.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file validation rules
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products'); // Store the image in the 'public/products' directory
            $data['image'] = $imagePath;
        }

        $this->product->create($data);

        return redirect()->route('product.index');
    }
    public function edit($id)
    {
        $product = $this->product->find($id);
        return view('product.edit', compact('product'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file validation rules
        ]);

        $product = $this->product->find($id);

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if (Storage::exists($product->image)) {
                Storage::delete($product->image);
            }

            $imagePath = $request->file('image')->store('products'); // Store the new image in the 'public/products' directory
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('product.index');
    }

    public function destroy($id)
    {
        $this->product->find($id)->delete();
        return redirect()->route('product.index');
    }

}
