<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Electronics Mart</title>
    
    <!-- Tailwind CSS and DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        .login-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }
        
        .input-field {
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #01949b 0%, #0f766e 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(1, 148, 155, 0.3), 0 4px 6px -2px rgba(1, 148, 155, 0.2);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md animate-fade-in">
        <div class="login-card rounded-3xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="zap" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Electronics Mart</h1>
                <p class="text-gray-500 font-medium">Admin Panel Access</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-error mb-6 rounded-xl">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="admin@electronicsmart.com" 
                            class="input-field w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white @error('email') border-red-300 @enderror" 
                            required 
                            autofocus
                        />
                    </div>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            class="input-field w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white @error('password') border-red-300 @enderror" 
                            required
                        />
                    </div>
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="btn-primary-custom w-full py-3 px-4 text-white font-semibold rounded-xl flex items-center justify-center space-x-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span>Sign In to Dashboard</span>
                </button>
            </form>
            
            <!-- Demo Credentials -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                        <h4 class="text-sm font-semibold text-blue-900">Demo Credentials</h4>
                    </div>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><span class="font-medium">Email:</span> admin@electronicsmart.com</p>
                        <p><span class="font-medium">Password:</span> password123</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                © 2024 Electronics Mart. All rights reserved.
            </p>
        </div>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>