<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '4-H LSA') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Charting & Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full overflow-hidden font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden bg-gray-100">

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm md:hidden" 
             @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 flex flex-col transition-transform duration-300 transform bg-white border-r shadow-2xl w-72 border-emerald-100 md:translate-x-0 md:static md:inset-0 md:shadow-none">
    
    {{-- Brand Header --}}
    <div class="p-8 border-b border-gray-50 bg-emerald-50/30">
        <div class="flex flex-col items-center">
            <div class="p-3 mb-4 transition-transform transform shadow-xl shadow-emerald-200/50 hover:rotate-6">
                <img src="{{ asset('images/logo.png') }}" class="object-contain w-24 h-24" alt="4-H Official Logo">
            </div>
            <h2 class="text-lg font-black leading-tight tracking-tight text-center text-slate-800">
                4-H CLUB <span class="uppercase text-emerald-600">Philippines</span>
            </h2>
            <div class="flex items-center mt-2 space-x-2">
                <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
                <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-[0.2em]">Information System</p>
                <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 mt-6 space-y-2 overflow-y-auto">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="group flex items-center space-x-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <i class="fas fa-chart-pie w-5 text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'group-hover:text-emerald-600' }}"></i>
            <span class="text-sm font-bold">System Dashboard</span>
        </a>

        {{-- Member Management Group --}}
        <div class="px-4 pt-4 pb-2">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Registry Management</p>
        </div>

        <a href="{{ route('members.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('members.index') || request()->routeIs('members.edit') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-600 font-bold' : 'text-slate-500 hover:bg-gray-50' }}">
            <i class="w-5 fas fa-address-book"></i>
            <span class="text-sm">4-H Members Directory</span>
        </a>

        <a href="{{ route('organizations.index') }}" 
            class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('organizations.index') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-600 font-bold' : 'text-slate-500 hover:bg-gray-50' }}">
            <i class="w-5 fas fa-sitemap"></i>
            <span class="text-sm">New Organization Registration</span>
        </a>

        @if(auth()->user()->role === 'Admin')
            <div class="px-4 pt-6 pb-2 mt-6 border-t border-gray-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Administrator</p>
            </div><a href="{{ route('users.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('users.*') ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100' }}">
                <i class="w-5 fas fa-users-cog"></i>
                <span class="text-sm font-bold">User Access</span>
            </a><a href="{{ route('announcements.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100' }}">
                <i class="text-indigo-500 fas fa-bullhorn"></i>
                <span class="text-sm font-bold">Announcements</span>
            </a>
        @endif
    </nav>
    <div class="p-4 border-t border-emerald-50 bg-slate-50/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-between w-full px-4 py-3 text-red-600 transition-all bg-white border border-red-100 shadow-sm rounded-xl hover:bg-red-50 hover:border-red-200 group">
                <div class="flex items-center space-x-3">
                    <i class="text-sm transition-transform fas fa-sign-out-alt group-hover:-translate-x-1"></i>
                    <span class="text-xs font-black tracking-widest uppercase">Sign Out</span>
                </div>
                <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
            </button>
        </form>
    
        {{-- User Profile Snippet (Optional) --}}
        <div class="flex items-center px-2 mt-4 space-x-3">
            <div
                class="h-8 w-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-[10px] font-bold shadow-lg shadow-emerald-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-slate-800 truncate uppercase">{{ auth()->user()->name }}</p>
                <p class="text-[9px] font-bold text-slate-400 truncate tracking-tighter">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside>

        {{-- Content Wrapper --}}
        <div class="relative flex flex-col flex-1 min-w-0 overflow-hidden">
            
            {{-- Top Navbar --}}
            <header class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200 md:px-8 shrink-0">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 text-gray-500 transition-colors md:hidden hover:text-green-600">
                        <i class="text-xl fas fa-bars"></i>
                    </button>
                    <div class="ml-2 md:ml-0">
                        <span class="hidden text-xs font-bold tracking-widest text-gray-400 uppercase lg:inline-block">
                            National Management Information System
                        </span>
                        <span class="text-xs font-bold text-gray-400 uppercase lg:hidden">4-H LSA</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-full">
                        <div class="h-2 w-2 rounded-full {{ auth()->user()->role === 'Admin' ? 'bg-indigo-500 animate-pulse' : 'bg-green-500' }}"></div>
                        <span class="text-[10px] font-black text-gray-600 uppercase">
                            ROLE: {{ auth()->user()->role }} <br>
                            POSITION: {{ auth()->user()->position }}<br>
                            
                            @if(auth()->user()->Region)
                                REGION: {{ auth()->user()->Region->name }}
                            @else
                                SCOPE: Global Oversight
                            @endif
                        </span>
                    </div>
                </div>
            </header>

            {{-- Main Content Scroll Area --}}
            <main class="flex-1 overflow-y-auto focus:outline-none bg-gray-50/50">
                @isset($header)
                    <div class="px-4 py-6 bg-white border-b border-gray-200 md:px-8">
                        <h2 class="text-2xl font-black tracking-tight text-gray-900">
                            {{ $header }}
                        </h2>
                    </div>
                @endisset

                <div class="p-4 md:p-8">
                    <div class="mx-auto max-w-7xl">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>

</body>
</html>