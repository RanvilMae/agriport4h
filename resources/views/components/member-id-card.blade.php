@props(['member'])

<div class="relative w-[350px] h-[550px] bg-white rounded-[3rem] shadow-2xl shadow-emerald-100 border border-slate-100 overflow-hidden font-sans mx-auto print:shadow-none print:border-slate-300">
    
    {{-- Dynamic Tier Header --}}
    <div class="h-44 p-8 flex flex-col justify-between transition-colors duration-700
        {{ $member->lsa_level === 'Platinum' ? 'bg-slate-900' :
    ($member->lsa_level === 'Gold' ? 'bg-amber-500' : 'bg-emerald-600') }}">
        
        <div class="flex items-start justify-between">
            <span class="text-white/60 text-[10px] font-black uppercase tracking-[0.3em]">Official ID</span>
           
        </div>

        {{-- 4-H Logo Integration --}}
        <div class="flex items-center space-x-3 text-white">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center p-1.5 shadow-lg">
                <img src="{{ asset('images/logo.png') }}" alt="4-H Logo" class="object-contain w-full h-full">
            </div>
            <div>
                <h4 class="text-xs font-black leading-none tracking-widest uppercase">4-H Club Philippines</h4>
                <p class="text-[8px] font-bold opacity-80 uppercase tracking-tighter mt-1 italic">Official Member ID</p>
            </div>
        </div>
    </div>

    {{-- Profile Avatar Section --}}
    <div class="absolute left-0 right-0 flex justify-center top-36">
        <div class="p-2 bg-white rounded-[2.5rem] shadow-xl">
            <div class="w-28 h-28 bg-slate-100 rounded-[2rem] border-4 border-white flex items-center justify-center text-3xl font-black text-emerald-600 overflow-hidden">
                @if($member->profile_photo_path)
                    <img src="{{ asset('storage/' . $member->profile_photo_path) }}" class="object-cover w-full h-full">
                @else
                    <span class="uppercase opacity-30">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Member Details --}}
    <div class="px-8 mt-24 text-center">
        <h2 class="text-2xl font-black leading-tight text-slate-800">
            {{ $member->first_name }} <br> {{ $member->last_name }}
        </h2>
        <p class="mt-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 inline-block px-4 py-1 rounded-full border border-emerald-100">
            {{ $member->specialization ?? 'General Member' }}
        </p>
    </div>

    {{-- Integrated QR ID Section with New UID Format --}}
    <div class="px-8 mt-6">
        <div class="p-4 bg-slate-50 rounded-[2.5rem] flex flex-col items-center border border-slate-100">
            <div class="p-3 bg-white border shadow-inner rounded-2xl border-slate-100">
                {{-- QR now generates based on the unique incremental UID --}}
                {!! QrCode::size(110)->margin(1)->color(30, 41, 59)->generate($member->uid ?? $member->member_id) !!}
            </div>
            <div class="flex flex-col items-center mt-3">
                <span class="text-[7px] font-black text-slate-300 uppercase tracking-[0.2em]">Member ID</span>
                <p class="text-[10px] font-mono text-slate-600 font-bold tracking-normal">
                    {{ $member->uid }}
                </p>
            </div>
        </div>
    </div>

    {{-- Footer/Location --}}
    <div class="absolute flex items-end justify-between pt-4 border-t bottom-8 left-8 right-8 border-slate-50">
        <div>
            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">Region</span>
            <span class="text-[10px] font-bold text-slate-600">{{ $member->region->region_code ?? 'N/A' }}</span>
        </div>
        <div class="text-right">
            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">Issued Date</span>
            <span class="text-[10px] font-bold text-slate-600">{{ now()->format('M Y') }}</span>
        </div>
    </div>
</div>