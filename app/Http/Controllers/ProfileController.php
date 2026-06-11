<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $ads = Ad::where('user_id', $user->id)
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_ads' => Ad::where('user_id', $user->id)->count(),
            'total_views' => Ad::where('user_id', $user->id)->sum('views'),
            'active_ads' => Ad::where('user_id', $user->id)->where('is_active', true)->count(),
        ];
        
        return view('profile.index', compact('user', 'ads', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:100',
        ]);
        
        $user->update($request->only(['name', 'email', 'phone', 'whatsapp', 'location']));
        
        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully!');
    }
}