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
        // return response()->json(['results' => $results]);
        return view('customer.results', compact('results'));

    }

    public function userSearch(Request $request)
    {
        $query = $request->input("query");
        $results = User::where("name", "LIKE", "%{$query}%")
                    ->where('usertype', 'user')
                    ->with('verification')
                    ->get()
                    ->map(function($user) {
                        return [
                            'name' => $user->name,
                            'email' => $user->email,
                            'email_verified_at' => $user->email_verified_at,
                            'verification_status' => $user->verification->status ?? 'verified', 
                            'id' => $user->id
                        ];
                    });

        return response()->json(['results' => $results]);
    }


}
