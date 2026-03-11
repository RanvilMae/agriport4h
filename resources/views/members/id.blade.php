<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-xl mx-auto px-4">
            
            {{-- Navigation/Actions --}}
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('members.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-emerald-600 transition">
                    &larr; Back to Directory
                </a>
                <button onclick="window.print()" class="bg-white border border-slate-200 px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition">
                    Print Physical Card
                </button>
            </div>

            {{-- The ID Component --}}
            <x-member-id-card :member="$member" />

            {{-- Post-Generation Tips --}}
            <div class="mt-12 p-6 bg-emerald-900 rounded-[2.5rem] shadow-2xl text-white">
                <div class="flex items-start space-x-4">
                    <div class="bg-emerald-500/20 p-3 rounded-2xl">
                        <i class="fas fa-magic text-emerald-400"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest">Smart ID Integration</h4>
                        <p class="text-xs text-emerald-200/70 mt-1 leading-relaxed">
                            This QR Code is unique to <strong>{{ $member->email }}</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Style Overrides --}}
    <style>
        @media print {
            body * { visibility: hidden; }
            .print\:shadow-none, .print\:shadow-none * { visibility: visible; }
            .max-w-xl { position: absolute; left: 0; top: 0; width: 100%; margin: 0; }
            button, a { display: none !important; }
        }
    </style>
</x-app-layout>