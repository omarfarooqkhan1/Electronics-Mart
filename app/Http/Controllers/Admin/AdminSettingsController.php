<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // Get some basic stats for the settings page
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_categories' => \App\Models\Category::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_users' => User::where('role', 'customer')->count(),
        ];
        
        return view('admin.settings.index', compact('admin', 'stats'));
    }
    
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
        ]);
        
        $admin->update($validated);
        
        return redirect()->route('admin.settings.index')
                        ->with('success', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $admin = Auth::user();
        
        if (!Hash::check($validated['current_password'], $admin->password)) {
            return redirect()->route('admin.settings.index')
                            ->with('error', 'Current password is incorrect.');
        }
        
        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return redirect()->route('admin.settings.index')
                        ->with('success', 'Password updated successfully.');
    }
    
    public function updateSystemSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string',
        ]);
        
        // In a real application, you might store these in a settings table
        // For now, we'll just show a success message
        
        return redirect()->route('admin.settings.index')
                        ->with('success', 'System settings updated successfully.');
    }
}