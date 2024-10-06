<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function addProduct(Request $request) {

        // dd($request->all());
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product;

        // Fill the product attributes from the request data
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->description = $request->description;
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

    public function update(Request $request, $id) {

        // dd($request->all());
        // Validate incoming data
        $validatedData = $request->validate([
            'product_name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'stockQuantity' => 'required|numeric',
            'category_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Add any validation rules for the image
        ]);

        $product = Product::findOrFail($id);

        // Update product attributes
        $product->product_name = $validatedData['product_name'];
        $product->price = $validatedData['price'];
        $product->description = $validatedData['description'];
        $product->stockQuantity = $validatedData['stockQuantity'];
        $product->category_id = $validatedData['category_id'];

        // Handle image upload if provided
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $product->photo = $path;
        }

        $product->save();

        return redirect()->route('product')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product')->with('success', 'Product successfully deleted');
    }
}
