<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-gray-800 uppercase">Edit User: {{ $user->name }}</h2>
    </x-slot>

    <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-8 bg-white border border-gray-100 shadow-sm rounded-2xl">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name Field --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full p-4 mt-1 font-bold text-gray-700 border-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500/20">
                </div>

                {{-- Region Field --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Assign Region</label>
                    <select name="region_id" class="w-full p-4 mt-1 font-bold text-gray-700 border-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Select Region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $user->region_id == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Role Selection --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">System Role</label>
                        <select name="role" class="w-full p-4 mt-1 font-bold text-gray-700 border-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500/20">
                            <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin (Global/National)</option>
                            <option value="Coordinator" {{ $user->role === 'Coordinator' ? 'selected' : '' }}>Coordinator (Regional/Provincial/Municipal/Club)</option>
                            <option value="President" {{ $user->role === 'President' ? 'selected' : '' }}>President (Regional/Provincial/Municipal/Club)</option>
                            <option value="Member" {{ $user->role === 'Member' ? 'selected' : '' }}>Member (Individual)</option>
                        </select>
                    </div>
                
                    {{-- Access Status --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Account Status</label>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="checkbox" name="is_accepted" value="1" {{ $user->is_accepted ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-bold text-gray-600">Verified & Approved Access</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 text-xs font-black text-white uppercase transition-all bg-blue-600 shadow-lg rounded-xl shadow-blue-900/20 hover:bg-blue-700">
                        Update User Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>