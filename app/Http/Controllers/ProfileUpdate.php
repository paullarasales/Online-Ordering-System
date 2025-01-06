<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileUpdate extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'contact_number' => 'required|digits:10',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Prepare the data to update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'municipality' => $request->municipality,
            'address' => $request->address,
            'contact_number' => '+63' . $request->contact_number,
        ];

        // Handle the photo upload if present
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('profile', 'public');
            $data['photo'] = $imagePath;

            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
        }

        // Update user information using query builder
        DB::table('users')->where('id', $user->id)->update($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile updated');
    }

    public function destroy()
    {
        // Code for destroying profile if needed
    }
}
