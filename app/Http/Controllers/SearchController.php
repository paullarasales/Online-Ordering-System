<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input("query");
        $results = Product::where("product_name", "LIKE", "%{$query}%")->get();
        return view("customer.results", ["results" => $results]);
    }

    public function userSearch(Request $request)
    {
        $query = $request->input("query");
        $results = User::where("name", "LIKE", "%{$query}%")->get();
        return response()->json(['results' => $results]);
    }
}
