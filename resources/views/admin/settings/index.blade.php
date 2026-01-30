@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-description', 'Manage your admin account and system settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center">
                <i data-lucide="settings" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-500 font-medium">Manage your account and system preferences</p>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Profile Settings -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                Profile Settings
            </h2>
            
            <form method="POST" action="{{ route('admin.settings.update-profile') }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Full Name *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $admin->name) }}"
                            placeholder="Your full name"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                            required
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Email Address *</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $admin->email) }}"
                            placeholder="your@email.com"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-300 @enderror"
                            required
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update Profile
                    </button>
                </div>
            </form>
        </section>

        <!-- Password Settings -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-red-600"></i>
                Change Password
            </h2>
            
            <form method="POST" action="{{ route('admin.settings.update-password') }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Current Password *</label>
                        <input
                            type="password"
                            name="current_password"
                            placeholder="Enter your current password"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('current_password') border-red-300 @enderror"
                            required
                        />
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase text-gray-400">New Password *</label>
                            <input
                                type="password"
                                name="password"
                                placeholder="Enter new password"
                                class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('password') border-red-300 @enderror"
                                required
                            />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase text-gray-400">Confirm New Password *</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm new password"
                                class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                required
                            />
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <i data-lucide="key" class="w-4 h-4"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </section>

        <!-- System Settings -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="globe" class="w-5 h-5 text-green-600"></i>
                System Settings
            </h2>
            
            <form method="POST" action="{{ route('admin.settings.update-system') }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Site Name *</label>
                        <input
                            type="text"
                            name="site_name"
                            value="{{ old('site_name', 'Electronics Mart') }}"
                            placeholder="Electronics Mart"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('site_name') border-red-300 @enderror"
                            required
                        />
                        @error('site_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Contact Email *</label>
                        <input
                            type="email"
                            name="contact_email"
                            value="{{ old('contact_email', 'contact@electronicsmart.com') }}"
                            placeholder="contact@electronicsmart.com"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('contact_email') border-red-300 @enderror"
                            required
                        />
                        @error('contact_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Contact Phone</label>
                        <input
                            type="tel"
                            name="contact_phone"
                            value="{{ old('contact_phone', '+49-30-12345678') }}"
                            placeholder="+49-30-12345678"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('contact_phone') border-red-300 @enderror"
                        />
                        @error('contact_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Currency *</label>
                        <select
                            name="currency"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 transition-all font-medium @error('currency') border-red-300 @enderror"
                            required
                        >
                            <option value="EUR" {{ old('currency', 'EUR') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                        </select>
                        @error('currency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase text-gray-400">Timezone *</label>
                        <select
                            name="timezone"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 transition-all font-medium @error('timezone') border-red-300 @enderror"
                            required
                        >
                            <option value="Europe/Berlin" {{ old('timezone', 'Europe/Berlin') === 'Europe/Berlin' ? 'selected' : '' }}>Berlin, Germany (CET)</option>
                            <option value="UTC" {{ old('timezone') === 'UTC' ? 'selected' : '' }}>Coordinated Universal Time (UTC)</option>
                            <option value="America/New_York" {{ old('timezone') === 'America/New_York' ? 'selected' : '' }}>Eastern Time (EST/EDT)</option>
                            <option value="Europe/London" {{ old('timezone') === 'Europe/London' ? 'selected' : '' }}>London, UK (GMT/BST)</option>
                        </select>
                        @error('timezone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Site Description</label>
                    <textarea
                        name="site_description"
                        rows="3"
                        placeholder="Premium electronics and appliances for modern homes"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 transition-all resize-none @error('site_description') border-red-300 @enderror"
                    >{{ old('site_description', 'Premium electronics and appliances for modern homes') }}</textarea>
                    @error('site_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Address</label>
                    <textarea
                        name="address"
                        rows="3"
                        placeholder="123 Electronics Street, Berlin, Germany"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 transition-all resize-none @error('address') border-red-300 @enderror"
                    >{{ old('address', '123 Electronics Street, Berlin, Germany') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update Settings
                    </button>
                </div>
            </form>
        </section>

        <!-- System Statistics -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600"></i>
                System Statistics
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                    <p class="text-sm text-gray-600">Products</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="tags" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                    <p class="text-sm text-gray-600">Categories</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-teal-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                    <p class="text-sm text-gray-600">Orders</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-600">Customers</p>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection