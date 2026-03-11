<x-app-layout>
    <x-slot name="header">
        Edit LSA Member: {{ $member->full_name }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('members.index') }}" class="text-sm text-gray-500 hover:text-green-600 font-bold flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Directory
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('members.update', $member->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT') {{-- Required for Laravel Updates --}}

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}" required
                               class="w-full border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Region --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Region</label>
                            <select name="region_id" class="w-full border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500">
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $member->region_id == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LSA Level --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">LSA Level</label>
                            <select name="lsa_level_id" class="w-full border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500">
                                @foreach($lsaLevels as $level)
                                    <option value="{{ $level->id }}" {{ $member->lsa_level_id == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Province --}}
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Province</label>
                        <input type="text" name="province" value="{{ old('province', $member->province) }}" required
                               class="w-full border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="pt-4 flex items-center justify-end space-x-3">
                        <a href="{{ route('members.index') }}" class="text-gray-400 font-bold text-sm">Cancel</a>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-green-100 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>