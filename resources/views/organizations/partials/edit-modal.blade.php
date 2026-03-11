<div x-data="{ 
        open: false, 
        name: '', 
        id: '', 
        action: '' 
    }" 
    @open-edit-modal.window="open = true; id = $event.detail.id; name = $event.detail.name; action = '/organizations/' + $event.detail.id"
    x-show="open" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto">
    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md p-8 bg-white shadow-2xl rounded-3xl">
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black tracking-tight uppercase text-slate-800">Edit Organization</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form :action="action" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Organization Name</label>
                        <input type="text" name="name" x-model="name" required
                               class="w-full px-4 py-3 mt-1 text-sm font-semibold border-gray-100 bg-gray-50 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <div class="flex justify-end mt-8 space-x-3">
                    <button type="button" @click="open = false" 
                            class="px-5 py-2.5 text-xs font-bold text-slate-500 uppercase hover:bg-slate-50 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 text-xs font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all">
                        Update Organization
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>