<!-- Global Confirmation Modal (Tailwind + Alpine) -->
<div 
    x-data="{ 
        open: false, 
        title: 'Confirm Action', 
        message: 'Are you sure?', 
        warning: '', 
        confirmCallback: null,
        show(config) {
            this.title = config.title || 'Confirm Action';
            this.message = config.message || 'Are you sure?';
            this.warning = config.warning || '';
            this.confirmCallback = config.callback;
            this.open = true;
        },
        confirm() {
            if (this.confirmCallback) this.confirmCallback();
            this.open = false;
        }
    }"
    x-show="open"
    x-on:open-confirm-modal.window="show($event.detail)"
    class="fixed inset-0 z-[100] overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="open = false"
    ></div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden relative"
        >
            <div class="px-10 pt-10 pb-6 border-b border-slate-50 flex items-center justify-between">
                <h3 x-text="title" class="text-xl font-black text-slate-900 tracking-tight"></h3>
                <button @click="open = false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <div class="px-10 py-8">
                <p x-text="message" class="text-slate-600 font-medium leading-relaxed"></p>
                <div x-show="warning" class="mt-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl">
                    <p x-text="warning" class="text-[10px] font-black text-amber-800 uppercase tracking-widest"></p>
                </div>
            </div>

            <div class="px-10 py-8 bg-slate-50/50 flex items-center justify-end gap-3">
                <button @click="open = false" class="px-8 py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">
                    Cancel
                </button>
                <button @click="confirm()" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20">
                    Confirm Action
                </button>
            </div>
        </div>
    </div>
</div>
