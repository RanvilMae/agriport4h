<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800">Member Digital ID</h2>
            <a href="{{ route('members.index') }}" class="text-sm text-gray-500 hover:text-gray-800 transition-colors">
                &larr; Back to Directory
            </a>
        </div>
    </x-slot>

    <div class="max-w-md mx-auto py-12 px-4">
        {{-- ID Card Container --}}
        <div
            class="bg-white rounded-[2.5rem] shadow-2xl shadow-green-100 border border-gray-100 overflow-hidden relative">

            {{-- Top Accent / Tier Color --}}
            <div class="h-32 bg-gradient-to-br from-green-500 to-green-700 p-6 flex justify-between items-start">
                <span class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em]">Member ID Card</span>
                {{-- Tier Badge --}}
                <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/30">
                    <span class="text-white text-[10px] font-bold uppercase tracking-wider">
                        {{ $member->lsa_level ?? 'Standard' }}
                    </span>
                </div>
            </div>

            {{-- Profile Section --}}
            <div class="px-8 -mt-12 text-center">
                <div class="inline-block p-2 bg-white rounded-[2rem] shadow-lg">
                    <div
                        class="w-24 h-24 bg-gray-100 rounded-[1.5rem] flex items-center justify-center text-3xl font-bold text-green-600 border-2 border-green-50">
                        {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                    </div>
                </div>

                <h1 class="mt-4 text-2xl font-black text-gray-800">
                    {{ $member->first_name }} {{ $member->last_name }}
                </h1>
                <p class="text-sm text-gray-400 font-medium">{{ $member->specialization }}</p>
            </div>

            {{-- QR Code Section (The Functional ID) --}}
            <div class="p-8 mt-4 flex flex-col items-center border-t border-b border-gray-50 bg-gray-50/30">
                <div class="bg-white p-4 rounded-3xl shadow-inner border border-gray-100">
                    {{-- Assuming you use a library like simplesoftwareio/simple-qrcode --}}
                    {{-- The QR contains the unique Email ID / JWT --}}
                    {!! QrCode::size(160)->margin(1)->color(31, 41, 55)->generate($member->email) !!}
                </div>
                <div class="mt-4 text-center">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Unique
                        Identifier</span>
                    <code class="text-xs font-mono text-green-600 bg-green-50 px-3 py-1 rounded-full">
                        {{ $member->email }}
                    </code>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="p-8 grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Location</span>
                    <p class="text-xs font-bold text-gray-700">{{ $member->province->name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">LSA Type</span>
                    <p class="text-xs font-bold text-gray-700 italic">{{ $member->lsa_type }}</p>
                </div>
            </div>

            {{-- Decorative Pattern --}}
            <div class="absolute bottom-0 right-0 p-4 opacity-5">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM13.536 14.95a1 1 0 010-1.414l.707-.707a1 1 0 011.414 1.414l-.707.707a1 1 0 01-1.414 0zM6.464 14.95l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414z" />
                </svg>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 flex gap-3">
            <button onclick="window.print()"
                class="flex-1 bg-gray-800 text-white py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-black transition-all">
                Print ID Card
            </button>
            <button
                class="flex-1 bg-white text-gray-700 border border-gray-200 py-3 rounded-2xl font-bold text-sm hover:bg-gray-50 transition-all">
                Share Digital ID
            </button>
        </div>
    </div>
</x-app-layout>