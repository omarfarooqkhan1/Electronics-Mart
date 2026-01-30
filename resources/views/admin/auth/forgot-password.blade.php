<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Electronics Mart</title>
    
    <!-- Tailwind CSS and DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        
        :root {
            --neon-green: #7fff00;
            --neon-green-light: #9fff33;
            --neon-green-dark: #66cc00;
            --gradient-pink: #ff1493;
            --gradient-blue: #00bfff;
            --gradient-orange: #ff6600;
            --gradient-purple: #8a2be2;
            
            /* Updated color palette based on logo */
            --primary-50: #f0fff4;
            --primary-100: #dcfce7;
            --primary-200: #bbf7d0;
            --primary-300: #86efac;
            --primary-400: #4ade80;
            --primary-500: #7fff00;
            --primary-600: #66cc00;
            --primary-700: #4d9900;
            --primary-800: #336600;
            --primary-900: #1a3300;
            
            --secondary-50: #fdf2f8;
            --secondary-100: #fce7f3;
            --secondary-200: #fbcfe8;
            --secondary-300: #f9a8d4;
            --secondary-400: #f472b6;
            --secondary-500: #ff1493;
            --secondary-600: #db2777;
            --secondary-700: #be185d;
            --secondary-800: #9d174d;
            --secondary-900: #831843;
            
            --accent-50: #eff6ff;
            --accent-100: #dbeafe;
            --accent-200: #bfdbfe;
            --accent-300: #93c5fd;
            --accent-400: #60a5fa;
            --accent-500: #00bfff;
            --accent-600: #2563eb;
            --accent-700: #1d4ed8;
            --accent-800: #1e40af;
            --accent-900: #1e3a8a;
        }
        
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background-color: #f8fafc;
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
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            border: none;
            transition: all 0.3s ease;
            color: black;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(127, 255, 0, 0.3), 0 4px 6px -2px rgba(127, 255, 0, 0.2);
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
                <div class="w-24 h-24 bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-2xl flex items-center justify-center mx-auto mb-4 p-3 shadow-lg">
                    <img src="{{ asset('storage/logo.png') }}" alt="Electronics Mart Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Electronics Mart</h1>
                <p class="text-gray-500 font-medium">Reset your password</p>
            </div>
            
            @if(session('status'))
                <div class="alert alert-success mb-6 rounded-xl">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error mb-6 rounded-xl">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Reset Form -->
            <form method="POST" action="#" class="space-y-6">
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
                
                <button type="submit" class="btn-primary w-full py-3 px-4 font-semibold rounded-xl flex items-center justify-center space-x-2">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span>Send Reset Instructions</span>
                </button>
            </form>
            
            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('admin.login') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium inline-flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Back to Login</span>
                </a>
            </div>
            
            <!-- Info -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                        <h4 class="text-sm font-semibold text-blue-900">Password Reset</h4>
                    </div>
                    <p class="text-sm text-blue-800">
                        If you don't receive an email within a few minutes, please contact your system administrator.
                    </p>
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