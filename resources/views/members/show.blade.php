<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Member Digital ID</h2>
            <a href="{{ route('members.index') }}" class="text-sm font-bold transition-colors text-emerald-600 hover:text-emerald-800 print:hidden">
                &larr; Back to Directory
            </a>
        </div>
    </x-slot>

    <style>
        @media print {
            body { background: white; }
            nav, .print\:hidden, .mt-8 { display: none !important; }
            .max-w-md { margin: 0; padding: 0; max-width: 100%; }
            .id-card { 
                box-shadow: none !important; 
                border: 1px solid #e2e8f0 !important;
                margin: auto;
            }
        }
    </style>

    @php
        // Dynamic styling based on LSA Level
        $tier = $member->lsa_level ?? 'Standard';
        $gradient = match ($tier) {
            'Platinum' => 'from-slate-800 to-slate-950',
            'Gold' => 'from-amber-400 to-amber-600',
            default => 'from-emerald-500 to-emerald-700',
        };
    @endphp

    <div class="max-w-md px-4 py-12 mx-auto">
        {{-- ID Card Container --}}
        <div class="id-card bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-100 border border-gray-100 overflow-hidden relative">

            {{-- Top Accent / Tier Color --}}
            <div class="flex items-start justify-between h-32 p-6 bg-gradient-to-br {{ $gradient }}">
                <span class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em]">Official Member ID</span>
                
                {{-- Tier Badge --}}
                <div class="px-3 py-1 border rounded-full bg-white/20 backdrop-blur-md border-white/30">
                    <span class="text-white text-[10px] font-bold uppercase tracking-wider">
                        {{ $tier }}
                    </span>
                </div>
            </div>

            {{-- Profile Section --}}
            <div class="px-8 -mt-12 text-center">
                <div class="inline-block p-2 bg-white rounded-[2rem] shadow-lg">
                    <div class="w-24 h-24 bg-gray-50 rounded-[1.5rem] flex items-center justify-center text-3xl font-black text-emerald-600 border-2 border-emerald-50">
                        {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                    </div>
                </div>

                <h1 class="mt-4 text-2xl font-black tracking-tight text-gray-800">
                    {{ $member->first_name }} {{ $member->last_name }}
                </h1>
                <p class="text-xs font-bold tracking-widest uppercase text-emerald-600/70">{{ $member->specialization }}</p>
            </div>

            {{-- QR Code Section --}}
            <div class="flex flex-col items-center p-8 mt-4 border-t border-b border-gray-50 bg-gray-50/30">
                <div class="p-4 bg-white border border-gray-100 shadow-inner rounded-3xl">
                    {{-- QR now generates the raw Member ID data --}}
                    {!! QrCode::size(150)->margin(1)->color(31, 41, 55)->generate($member->member_id) !!}
                </div>
                <div class="mt-4 text-center">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Membership No.</span>
                    <code class="px-3 py-1 font-mono text-xs font-bold text-gray-600 rounded-full bg-gray-200/50">
                        {{ $member->member_id }}
                    </code>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="grid grid-cols-2 gap-4 p-8">
                <div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter block">Jurisdiction</span>
                    <p class="text-xs font-bold text-gray-700">{{ $member->province->name ?? 'N/A' }}, {{ $member->region->name ?? '' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter block">Registered Date</span>
                    <p class="text-xs font-bold text-gray-700">{{ $member->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- Decorative Watermark --}}
            <div class="absolute bottom-0 right-0 p-4 opacity-[0.03]">
                <i class="fas fa-shield-alt text-7xl"></i>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 mt-8 print:hidden">
            <button onclick="window.print()"
                class="flex-1 py-4 text-sm font-black text-white transition-all shadow-xl bg-slate-900 rounded-2xl hover:bg-black active:scale-95">
                <i class="mr-2 fas fa-print"></i> Print ID Card
            </button>
            <a href="mailto:{{ $member->email }}"
                class="flex-1 py-4 text-sm font-black text-center text-gray-700 transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:bg-gray-50 active:scale-95">
                <i class="mr-2 fas fa-share-alt"></i> Contact Member
            </a>
        </div>
    </div>
</x-app-layout>