<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // adjust max file size as needed
        ]);
        
        $product = new Product;
        
        // Fill the product attributes from the request data
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->stockQuantity = $request->stockQuantity;
        $product->category_id = $request->category_id; 

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Retrieve the uploaded file
            $file = $request->file('photo');

            // Generate a unique filename
            $filename = time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file in the 'public' disk under the 'products' directory
            $path = $file->storeAs('products', $filename, 'public');

            // Save the file path to the product instance
            $product->photo = $path;
        }

        // Save the product
        $product->save();

        // Redirect back with a success message
        return redirect()->route('product')->with('status', 'Successfully added');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
