<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50 print:bg-white print:py-0">
        <div class="max-w-xl px-4 mx-auto">
            
            {{-- Navigation: Hidden during print --}}
            <div class="flex items-center justify-between mb-8 print:hidden">
                <a href="{{ route('members.index') }}" class="text-xs font-black tracking-widest uppercase transition text-slate-400 hover:text-emerald-600">
                    &larr; Back to Directory
                </a>
                <button onclick="window.print()" class="bg-white border border-slate-200 px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition active:scale-95">
                    <i class="mr-2 fas fa-print"></i> Print Physical Card
                </button>
            </div>

            {{-- The ID Component --}}
            <x-member-id-card :member="$member" />

            {{-- Informational Box: Hidden during print --}}
            <div class="p-6 mt-12 bg-slate-900 rounded-[2.5rem] shadow-2xl text-white print:hidden">
                <div class="flex items-start space-x-4">
                    <div class="p-3 bg-emerald-500/20 rounded-2xl">
                        <i class="text-emerald-400 fas fa-shield-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black tracking-widest uppercase">Encrypted Verification</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-400">
                            Scanning this QR Code links directly to the secure registry record for <strong>{{ $member->member_id }}</strong>. Use this for field inspections or event check-ins.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Precise Print Overrides --}}
    <style>
        @media print {
            nav, .print\:hidden, .mt-12, .mb-8 { display: none !important; }
            body { background: white; }
            .max-w-xl { max-width: 100%; width: 100%; margin: 0; padding: 0; }
            .id-card { 
                margin: 20px auto;
                width: 350px; /* Standard ID Width */
                border: 1px solid #eee;
            }
        }
    </style>
</x-app-layout>