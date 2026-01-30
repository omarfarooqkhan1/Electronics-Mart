<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - NI Drip Central</title>
    
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
        
        .sidebar-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-right: 2px solid var(--neon-green);
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-shadow-lg {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, var(--neon-green) 0%, var(--neon-green-dark) 100%);
            color: black;
            box-shadow: 0 4px 12px rgba(127, 255, 0, 0.3);
            font-weight: 700;
        }
        
        .nav-item {
            color: #374151;
            transition: all 0.2s ease-in-out;
        }
        
        .nav-item:hover:not(.active) {
            background: rgba(127, 255, 0, 0.1);
            color: var(--neon-green-dark);
            transform: translateX(4px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile sidebar backdrop -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        <div 
            x-show="sidebarOpen || window.innerWidth >= 1024"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 sidebar-gradient border-r border-gray-200 lg:static lg:inset-0 animate-slide-in"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <!-- NI Drip Central Logo -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center relative overflow-hidden">
                        <!-- Gradient background mimicking the logo -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-500 via-blue-500 to-orange-500 rounded-lg"></div>
                        <div class="relative z-10 w-6 h-6 bg-white rounded-sm flex items-center justify-center">
                            <span class="text-black font-bold text-xs">N</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">NI Drip Central</h2>
                        <p class="text-xs text-gray-500 font-medium">Admin Panel</p>
                    </div>
                </div>
                <button 
                    @click="sidebarOpen = false"
                    class="lg:hidden p-1 rounded-md text-gray-400 hover:text-gray-600"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</h3>
                    </div>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                        Products
                        @php
                            $productCount = \App\Models\Product::count();
                        @endphp
                        <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $productCount }}</span>
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i data-lucide="tags" class="w-5 h-5 mr-3"></i>
                        Categories
                        @php
                            $categoryCount = \App\Models\Category::count();
                        @endphp
                        <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $categoryCount }}</span>
                    </a>
                </div>
                
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales</h3>
                    </div>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i>
                        Orders
                        @php
                            $pendingOrders = \App\Models\Order::where('status', 'processing')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="ml-auto bg-gradient-to-r from-pink-500 to-orange-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                        <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                        Customers
                    </a>
                </div>
                
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Analytics</h3>
                    </div>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        Reports
                    </a>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                        <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i>
                        Analytics
                    </a>
                </div>
                
                <div class="pt-6 border-t border-gray-200 space-y-2">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                    
                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl w-full text-left hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
            
            <!-- Admin Info -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-500 via-blue-500 to-orange-500 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'Admin User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@nidripcentral.com' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button 
                            @click="sidebarOpen = true"
                            class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                        >
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500 mt-1">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your store today.')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle relative">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                @php
                                    $pendingOrdersCount = \App\Models\Order::where('status', 'processing')->count();
                                @endphp
                                @if($pendingOrdersCount > 0)
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                                @endif
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-white rounded-xl w-80 border border-gray-200">
                                <li class="menu-title text-sm font-semibold">Notifications</li>
                                @if($pendingOrdersCount > 0)
                                    <li><a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-sm">{{ $pendingOrdersCount }} new orders pending</a></li>
                                @endif
                                <li><a href="#" class="text-sm">System backup completed</a></li>
                                <li><a href="#" class="text-sm">New customer registered</a></li>
                            </ul>
                        </div>
                        
                        <!-- Quick actions -->
                        <button class="btn btn-sm bg-gradient-to-r from-lime-400 to-lime-600 text-black border-none hover:from-lime-500 hover:to-lime-700 font-bold">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Add Product
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="alert alert-success mb-6 animate-fade-in">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error mb-6 animate-fade-in">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Handle responsive sidebar
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                Alpine.store('sidebarOpen', false);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>