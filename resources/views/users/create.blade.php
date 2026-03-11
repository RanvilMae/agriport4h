<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">System Access</span>
            <h2 class="font-black text-2xl text-slate-800 leading-tight">Create User Account</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 mt-8">
        {{-- Validation Error Summary --}}
        @if ($errors->any())
            <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <h3 class="text-sm font-black text-red-800 uppercase">Registration Failed</h3>
                </div>
                <ul class="text-xs text-red-600 space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form State with Alpine --}}
        <form action="{{ route('users.store') }}" method="POST" 
            x-data="{ 
                role: '{{ old('role', 'Member') }}',
                showRegion: true,
                init() { this.updateVisibility() },
                updateVisibility() {
                    // Logic: Hide region ONLY for Admin
                    this.showRegion = (this.role !== 'Admin');
                }
            }" 
            class="pb-32">
            @csrf

            {{-- Section 1: Account Identity --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-emerald-50 mb-8 overflow-hidden">
                <div class="bg-emerald-50/30 px-8 py-5 border-b border-emerald-50 flex justify-between items-center">
                    <h3 class="text-xs font-black text-emerald-700 uppercase tracking-[0.2em]">I. Account Identity</h3>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase">Step 1 of 2</span>
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('name') border-red-300 @enderror">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="w-full border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('email') border-red-300 @enderror"
                                placeholder="user@4hphilippines.org">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Password</label>
                            <input type="password" name="password" required 
                                class="w-full border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('password') border-red-300 @enderror">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required 
                                class="w-full border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Permissions & Assignment --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-emerald-50 mb-8 overflow-hidden">
                <div class="bg-emerald-50/30 px-8 py-5 border-b border-emerald-50 flex justify-between items-center">
                    <h3 class="text-xs font-black text-emerald-700 uppercase tracking-[0.2em]">II. Permissions & Assignment</h3>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase">Step 2 of 2</span>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Role Selection --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">System Role</label>
                            <select name="role" x-model="role" @change="updateVisibility()"
                                class="w-full border-gray-200 rounded-2xl bg-slate-50 focus:ring-emerald-500 transition-all font-bold text-slate-700">
                                <option value="Member">Member</option>
                                <option value="Coordinator">Coordinator</option>
                                <option value="President">President</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        {{-- Position --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Official Position</label>
                            <input type="text" name="position" value="{{ old('position') }}"
                                placeholder="e.g. Regional Representative"
                                class="w-full border-gray-200 rounded-2xl focus:ring-emerald-500 transition-all">
                        </div>
                    </div>

                    <hr class="border-emerald-50">

                    {{-- Region Selection (Conditional) --}}
                    <div x-show="showRegion" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2"
                        class="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100">
                        <label class="block text-[10px] font-black text-emerald-700 uppercase mb-3 ml-1">Regional Jurisdiction</label>
                        <select name="region_id" :required="showRegion"
                            class="w-full border-emerald-100 rounded-xl focus:ring-emerald-500 shadow-sm font-medium @error('region_id') border-red-300 @enderror">
                            <option value="">Select Region...</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }} ({{ $region->region_code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="flex items-center mt-4 text-emerald-600">
                            <i class="fas fa-info-circle text-xs mr-2"></i>
                            <p class="text-[11px] font-bold uppercase tracking-tighter">Assigned users can only manage data within this region.</p>
                        </div>
                    </div>

                    {{-- Message for Admins --}}
                    <div x-show="!showRegion" x-transition x-cloak
                        class="p-6 bg-slate-800 rounded-[2rem] border border-slate-700 flex items-center space-x-4 shadow-xl shadow-slate-200">
                        <div class="bg-emerald-500 p-2.5 rounded-xl">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-white font-bold uppercase tracking-wider">System Administrator Account</p>
                            <p class="text-[10px] text-slate-400 mt-1">Full global access enabled. No regional restrictions apply.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Action Bar --}}
            <div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-emerald-100 p-5 z-50">
                <div class="max-w-4xl mx-auto flex justify-between items-center">
                    <a href="{{ route('users.index') }}" class="text-xs font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition">Cancel</a>
                    <button type="submit"
                        class="bg-emerald-600 text-white font-black py-4 px-12 rounded-2xl shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-widest text-sm">
                        Finalize Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>