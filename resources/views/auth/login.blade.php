<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>4-H Club | Information System</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .clover-bg {
            background-color: #228b22; /* 4-H Green */
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased bg-slate-50">

    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-12">

        {{-- LEFT PANEL: 4-H Identity --}}
        <div class="relative flex-col items-center justify-center hidden p-20 overflow-hidden text-white lg:flex lg:col-span-7 clover-bg">
            <div class="relative z-10 text-center">
                <div class="inline-block p-8 bg-white rounded-[3rem] shadow-2xl mb-10 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                    <img src="{{ asset('images/logo.png') }}" class="object-contain w-48 h-48" alt="4-H Official Logo">
                </div>
                
                <h1 class="mb-4 text-6xl font-black tracking-tighter">4-H CLUB</h1>
                <p class="text-xl font-bold uppercase tracking-[0.4em] text-white/80">Head • Heart • Hands • Health</p>
                
                <div class="flex items-center justify-center gap-4 mt-12 opacity-60">
                    <div class="h-[1px] w-12 bg-white"></div>
                    <span class="text-xs font-black tracking-widest uppercase">Since 1952</span>
                    <div class="h-[1px] w-12 bg-white"></div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: Member Login --}}
        <div class="flex items-center justify-center p-8 bg-white lg:col-span-5">
            <div class="w-full max-w-md">
                
                <div class="mb-12 text-center">
                    {{-- Mobile Logo --}}
                    <div class="mb-8 lg:hidden">
                        <img src="{{ asset('images/logo.png') }}" class="w-auto h-20 mx-auto" alt="4-H">
                    </div>
                    
                    <h2 class="mb-3 text-4xl font-black tracking-tight text-slate-800">Member Login</h2>
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-[0.4em]">Youth Development Portal</p>
                </div>

                {{-- Success/Status Message (Optional) --}}
                @if (session('status'))
                    <div class="flex items-center gap-3 p-4 mb-6 text-xs font-bold border text-emerald-700 border-emerald-100 rounded-2xl bg-emerald-50">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Error Alert for Approval & Credentials --}}
                @if ($errors->any())
                    <div class="flex flex-col gap-2 p-5 mb-8 text-xs font-bold text-red-600 border border-red-100 shadow-sm rounded-2xl bg-red-50/50 animate-pulse">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-3">
                                <i class="text-sm fa-solid fa-shield-virus"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Member Email</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-6 transition-colors text-slate-300 group-focus-within:text-green-600">
                                <i class="text-lg fa-solid fa-user-graduate"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-14 pr-8 py-5 bg-slate-50 border-none rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-green-600/10 transition-all outline-none font-bold text-sm text-slate-700"
                                   placeholder="Enter registered email">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Security Key</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-green-600 hover:text-green-700 uppercase tracking-widest">Reset?</a>
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-6 transition-colors text-slate-300 group-focus-within:text-green-600">
                                <i class="text-lg fa-solid fa-shield-halved"></i>
                            </span>
                            <input type="password" name="password" required
                                   class="w-full pl-14 pr-8 py-5 bg-slate-50 border-none rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-green-600/10 transition-all outline-none font-bold text-sm text-slate-700"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-5 bg-[#228b22] hover:bg-[#1a6b1a] text-white font-black rounded-[1.5rem] shadow-xl shadow-green-900/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-4 uppercase tracking-[0.2em] text-xs">
                        <span>Access Portal</span>
                        <i class="text-sm fa-solid fa-leaf"></i>
                    </button>

                    <div class="pt-8 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                            New to the 4-H Club?
                        </p>
                        <a href="{{ route('register') }}" 
                        class="mt-2 inline-flex items-center gap-2 px-8 py-3 text-[10px] font-black text-green-700 uppercase tracking-widest transition-all border-2 border-green-100 rounded-full hover:bg-green-50 hover:border-green-200">
                            <span>Create Member Account</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] mt-12">
                            "To Make the Best Better"
                        </p>
                    </div>
                </form>
            </div>
            
        </div>

        
    </div>

</body>
</html>