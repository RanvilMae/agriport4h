<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white border shadow-sm rounded-xl border-emerald-50">
                    <i class="fas fa-sitemap text-emerald-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-tight text-gray-800">Organization Directory</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Regional Registry Management</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- Success & Error Notifications --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center p-4 mb-6 transition-all border shadow-sm bg-emerald-50 border-emerald-100 rounded-2xl">
                <i class="mr-3 text-sm fas fa-check-circle text-emerald-500"></i>
                <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.2em]">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-6 border shadow-sm bg-rose-50 border-rose-100 rounded-2xl">
                <div class="flex items-center mb-2 space-x-2 text-rose-600">
                    <i class="text-xs fas fa-exclamation-triangle"></i>
                    <h4 class="text-[10px] font-black uppercase tracking-widest">Action Denied</h4>
                </div>
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-[10px] font-bold text-rose-500 uppercase tracking-tight">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Search & Filter Toolbar --}}
        <form action="{{ route('organizations.index') }}" method="GET"
            class="flex flex-col items-center justify-between gap-4 p-5 mb-6 bg-white border border-gray-100 shadow-sm md:flex-row rounded-3xl">
            
            <div class="flex flex-1 w-full gap-3">
                {{-- Live Search Input --}}
                <div class="relative flex-grow max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="text-[10px] fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search organization or acronym..."
                        class="w-full py-3 pl-10 pr-4 text-xs font-bold transition-all border-none bg-slate-50 rounded-2xl focus:ring-2 focus:ring-emerald-500 placeholder:text-slate-400">
                </div>

                {{-- Region Filter --}}
                <select name="region" onchange="this.form.submit()"
                    class="px-4 py-3 text-[10px] font-black border-none bg-slate-50 rounded-2xl focus:ring-2 focus:ring-emerald-500 cursor-pointer uppercase tracking-widest text-slate-600">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
                            {{ $region->region_code ?? $region->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Manual Submit Button (Optional but helpful for search input) --}}
                <button type="submit" class="px-5 py-3 text-[10px] font-black text-white uppercase bg-emerald-600 rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                    Search
                </button>
            </div>

            <a href="{{ route('organizations.create') }}"
                class="w-full md:w-auto px-6 py-3.5 bg-slate-900 hover:bg-emerald-600 text-white text-[10px] font-black rounded-2xl transition-all shadow-xl shadow-slate-100 flex items-center justify-center uppercase tracking-[0.2em]">
                <i class="mr-2 fas fa-plus text-[8px]"></i> New Organization
            </a>
        </form>

        {{-- Table Container --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-[2.5rem] overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b bg-slate-50/50 border-gray-50">
                        <th class="px-8 py-5 text-xs font-black tracking-widest uppercase text-slate-500">Region</th>
                        <th class="px-4 py-5 text-xs font-black tracking-widest uppercase text-slate-500">Organization Name</th>
                        <th class="px-8 py-5 text-xs font-black tracking-widest text-right uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($organizations as $org)
                    <tr class="transition-colors hover:bg-slate-50/80 group">
                        <td class="px-8 py-4">
                            <span class="px-3 py-1 text-xs font-bold transition-all bg-white border rounded-lg shadow-sm border-slate-200 text-slate-600 group-hover:border-emerald-200">
                                {{ $org->region->region_code ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">{{ $org->name }}</span>
                                @if($org->acronym)
                                    <span class="text-xs font-medium uppercase text-emerald-600">{{ $org->acronym }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button @click="$dispatch('open-edit-modal', { id: {{ $org->id }}, name: '{{ $org->name }}' })" 
                                    class="text-sm font-bold transition-colors text-emerald-600 hover:text-emerald-800">
                                Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center text-slate-400">
                            <i class="mb-3 text-2xl fas fa-search opacity-20"></i>
                            <p class="text-xs font-bold tracking-widest uppercase">No organizations found matching your filters.</p>
                            @if(request()->anyFilled(['search', 'region']))
                                <a href="{{ route('organizations.index') }}" class="text-emerald-600 underline text-[10px] font-black mt-2 inline-block">CLEAR ALL FILTERS</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if(method_exists($organizations, 'links'))
                <div class="px-8 py-5 border-t bg-slate-50/50 border-gray-50">
                    {{ $organizations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Modal --}}
    @include('organizations.partials.edit-modal')

</x-app-layout>