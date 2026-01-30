<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Electronics Mart</title>
    
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
            --teal-50: #f0fdfa;
            --teal-100: #ccfbf1;
            --teal-200: #99f6e4;
            --teal-300: #5eead4;
            --teal-400: #2dd4bf;
            --teal-500: #14b8a6;
            --teal-600: #01949b;
            --teal-700: #0f766e;
            --teal-800: #115e59;
            --teal-900: #134e4a;
            
            --orange-50: #fff7ed;
            --orange-100: #ffedd5;
            --orange-200: #fed7aa;
            --orange-300: #fdba74;
            --orange-400: #fb923c;
            --orange-500: #fe690b;
            --orange-600: #ea580c;
            --orange-700: #c2410c;
            --orange-800: #9a3412;
            --orange-900: #7c2d12;
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
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-shadow-lg {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-item {
            transition: all 0.2s ease-in-out;
        }
        
        .nav-item:hover {
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, #01949b 0%, #0f766e 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(1, 148, 155, 0.3);
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
                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Electronics Mart</h2>
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
                   class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.dashboard*') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</h3>
                    </div>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.products.*') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                        Products
                        @php
                            $productCount = \App\Models\Product::count();
                        @endphp
                        <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $productCount }}</span>
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
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
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i>
                        Orders
                        @php
                            $pendingOrders = \App\Models\Order::where('order_status', 'processing')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100">
                        <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                        Customers
                    </a>
                </div>
                
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Analytics</h3>
                    </div>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        Reports
                    </a>
                    
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100">
                        <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i>
                        Analytics
                    </a>
                </div>
                
                <div class="pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                </div>
            </nav>
            
            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-700 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">Admin User</p>
                        <p class="text-xs text-gray-500 truncate">admin@electronicsmart.com</p>
                    </div>
                    <div class="dropdown dropdown-top dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-white rounded-xl w-52 border border-gray-200">
                            <li><a href="#" class="text-sm"><i data-lucide="user" class="w-4 h-4 mr-2"></i>Profile</a></li>
                            <li><a href="#" class="text-sm"><i data-lucide="settings" class="w-4 h-4 mr-2"></i>Settings</a></li>
                            <li class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-sm text-red-600 hover:bg-red-50">
                                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
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
                                    $pendingOrdersCount = \App\Models\Order::where('order_status', 'processing')->count();
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
                        <button class="btn btn-sm bg-gradient-to-r from-teal-500 to-teal-700 text-white border-none hover:from-teal-600 hover:to-teal-800">
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