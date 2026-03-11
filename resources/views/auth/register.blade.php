<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>4-H Club | Registration</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .clover-bg {
            background-color: #228b22; 
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased bg-slate-50">

    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-12">

        {{-- LEFT PANEL --}}
        <div class="relative flex-col items-center justify-center hidden p-20 overflow-hidden text-white lg:flex lg:col-span-7 clover-bg">
            <div class="relative z-10 text-center">
                <div class="inline-block p-8 bg-white rounded-[3rem] shadow-2xl mb-10 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                    <img src="{{ asset('images/logo.png') }}" class="object-contain w-48 h-48" alt="4-H Official Logo">
                </div>
                <h1 class="mb-4 text-6xl font-black tracking-tighter">JOIN THE 4-H CLUB OF THE PHILIPPINES</h1>
                <p class="text-xl font-bold uppercase tracking-[0.4em] text-white/80">Head • Heart • Hands • Health</p>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        {{-- RIGHT PANEL --}}
        <div class="flex items-center justify-center p-8 bg-white lg:col-span-5">
            <div class="w-full max-w-md py-12">

                {{-- Alert Section --}}
                <div class="mb-8">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="flex items-center gap-3 p-4 mb-4 text-sm font-bold duration-300 border shadow-sm border-emerald-100 rounded-2xl bg-emerald-50 text-emerald-700 animate-in fade-in slide-in-from-top-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- General Validation Errors (if any) --}}
                    @if ($errors->any())
                        <div class="flex items-start gap-3 p-4 mb-4 text-sm font-bold text-red-700 duration-300 border border-red-100 shadow-sm rounded-2xl bg-red-50 animate-in fade-in slide-in-from-top-2">
                            <i class="mt-1 text-red-500 fa-solid fa-triangle-exclamation"></i>
                            <div>
                                <p class="uppercase text-[10px] tracking-widest mb-1 opacity-70">Please check the following:</p>
                                <ul class="list-disc list-inside space-y-0.5 font-semibold">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-10 text-center">
                    <h2 class="mb-3 text-4xl font-black tracking-tight text-slate-800">New Account</h2>
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-[0.4em]">Registration Portal</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        {{-- Name --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-6 py-4 text-sm font-bold transition-all border-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700" placeholder="Name">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        {{-- Email --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-6 py-4 text-sm font-bold transition-all border-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 {{ $errors->has('email') ? 'focus:ring-red-600/10 ring-2 ring-red-500/20' : 'focus:ring-green-600/10' }} text-slate-700" 
                                placeholder="Email">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">System Role</label>
                        <select name="role" required class="w-full px-6 py-4 text-sm font-bold transition-all border-none appearance-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="President" {{ old('role') == 'President' ? 'selected' : '' }}>President</option>
                            <option value="Coordinator" {{ old('role') == 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                            <option value="Member" {{ old('role') == 'Member' ? 'selected' : '' }}>Member</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        {{-- Region --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Region</label>
                            <select name="region_id" class="w-full px-6 py-4 text-sm font-bold transition-all border-none appearance-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700">
                                <option value="">Select Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('region_id')" />
                        </div>
                        {{-- Position --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Position</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="w-full px-6 py-4 text-sm font-bold transition-all border-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700" placeholder="e.g. SDU Head">
                            <x-input-error :messages="$errors->get('position')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        {{-- Password --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" required class="w-full px-6 py-4 text-sm font-bold transition-all border-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700" placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                        {{-- Confirm --}}
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm</label>
                            <input type="password" name="password_confirmation" required class="w-full px-6 py-4 text-sm font-bold transition-all border-none bg-slate-50 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-600/10 text-slate-700" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-[#228b22] hover:bg-[#1a6b1a] text-white font-black rounded-2xl shadow-xl shadow-green-900/20 transition-all transform active:scale-[0.98] uppercase tracking-[0.2em] text-xs mt-4">
                        Register Account
                    </button>

                    <div class="pt-6 text-center">
                        <a href="{{ route('login') }}" class="text-[10px] font-black text-slate-400 hover:text-green-600 uppercase tracking-widest transition-colors">Already have an account? Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>