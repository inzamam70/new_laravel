<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    protected $product;
    public function __construct(Product $product){
        $this->product= $product;
    }

    public function index(){
        $products = $this->product->latest()->paginate(5);
        return view('product.index', compact('products'));
    }
    public function create(){
        return view('product.create');
    }
    public function store(Request $request){
        $this->product->create([
            'title' => $request->title,
            'description' => $request->description,
          
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $request->merge(['image' => $imageName]);
        }
        return redirect()->route('product.index');                
    }
    public function show($id)
    {
        $product = Product::find($id);
        
        return view('product.show', compact('product'));
    }
    public function edit($id)
    {
        $product = $this->product->find($id);
        return view('product.edit', compact('product'));
    }
    public function update(Request $request, $id)
    {
        $this->product->find($id)->update([
            'title' => $request->title,
            'description' => $request->description,

    
        ]);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $request->merge(['image' => $imageName]);
        }
      
        return redirect()->route('product.index');
    }
    public function destroy($id)
    {
        $this->product->find($id)->delete();
        return redirect()->route('product.index');
    }

}
