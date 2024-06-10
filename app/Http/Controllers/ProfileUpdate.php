<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileUpdate extends Controller
{
    public function update(Request $request){
        // dd($request->all());
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email, '.$user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg.gif|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('profile', 'public');
            $user->photo = $imagePath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated');
    }

    public function destroy() {

    }
}
