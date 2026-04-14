<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h2 class="text-2xl font-black leading-tight text-slate-800">Member Registry</h2>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-400">
                        {{ auth()->user()->role === 'Admin' ? 'Classified by Regional Jurisdiction' : 'Regional Member Directory' }}
                    </p>

                    @if($regions->count() === 1)
                        <span class="flex items-center gap-1.5 bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-tighter border border-emerald-200">
                            <i class="fas fa-lock text-[8px]"></i>
                            {{ $regions->first()->name }} Scope
                        </span>
                    @endif
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['Admin', 'President', 'Coordinator']))
            <a href="{{ route('members.create') }}"
                class="bg-emerald-600 text-white px-8 py-3 rounded-2xl text-sm font-black shadow-xl shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:scale-95">
                + Register Member
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Success/Error Notifications... (kept as provided) --}}

    <div class="px-4 py-8 mx-auto max-w-7xl">
        {{-- Search Bar... (kept as provided) --}}

        @forelse($members->groupBy('region.name') as $regionName => $regionMembers)
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-4 ml-4">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <div class="flex flex-col items-center">
                        <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.4em] bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100">
                            {{ $regionName ?? 'Unassigned Region' }}
                        </h3>
                        <span class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-widest">
                            {{ $regionMembers->count() }} Registered {{ Str::plural('Member', $regionMembers->count()) }}
                        </span>
                    </div>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-slate-50/50 border-slate-50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Name & ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Organization</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Specialization</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Verification</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">LSA Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($regionMembers as $member)
                                <tr class="transition-colors hover:bg-slate-50/50 group">
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black tracking-tight text-slate-800">
                                                {{ $member->last_name }}, {{ $member->first_name }}
                                            </span>
                                            <span class="text-[10px] font-mono text-emerald-500 font-bold uppercase tracking-tighter">{{ $member->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-bold text-slate-600">{{ $member->organization->name ?? 'Independent' }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-[10px] font-black text-slate-500 bg-slate-100 px-3 py-1 rounded-lg uppercase tracking-wider">
                                            {{ $member->specialization }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if($member->verified_at)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-double text-[8px]"></i> Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200">
                                                <i class="fas fa-clock text-[8px]"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $member->lsa_level == 'Platinum' ? 'bg-slate-900 text-emerald-400' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $member->lsa_level ?? 'Standard' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if(in_array(auth()->user()->role, ['President', 'Coordinator']))
                                                @if(!$member->verified_at)
                                                    <button 
                                                        onclick="confirmVerification('{{ $member->first_name }} {{ $member->last_name }}', '{{ $member->region_id }}', '{{ auth()->user()->region_id }}', '{{ route('members.verify', ['member_id' => $member->member_id]) }}')"
                                                        class="p-2.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-2xl transition-all group/btn" 
                                                        title="Verify Member">
                                                        <i class="transition-transform fas fa-shield-check group-hover/btn:scale-110"></i>
                                                    </button>
                                                @else
                                                    <div class="p-2.5 text-emerald-500 cursor-default" title="Verified">
                                                        <i class="fas fa-shield-bolt"></i>
                                                    </div>
                                                @endif
                                            @endif

                                            <a href="{{ route('members.show', $member->id) }}" class="p-2.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-2xl transition-all" title="View Digital ID">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            {{-- Empty State... --}}
        @endforelse
    </div>

    @push('scripts')
    {{-- Load Library inside push --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmVerification(name, memberRegion, userRegion, verifyUrl) {
            // Logic remains the same, but now library is guaranteed to load
            if (memberRegion != userRegion) {
                Swal.fire({
                    title: 'Access Denied',
                    text: 'You are only authorized to verify members within your assigned region.',
                    icon: 'error',
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'rounded-[2.5rem]', confirmButton: 'rounded-xl px-6 py-3 font-black text-sm uppercase' }
                });
                return;
            }

            Swal.fire({
                title: 'Verify Membership?',
                text: `Confirming active status for ${name}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Verify',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl px-6 py-3 font-black text-sm uppercase',
                    cancelButton: 'rounded-xl px-6 py-3 font-black text-sm uppercase'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = verifyUrl;
                }
            });
        }
    </script>
    @endpush
</x-app-layout>