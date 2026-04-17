{{-- Delete Confirmation Modal (Tailwind + Alpine) --}}
<div 
    x-data="{ 
        open: false, 
        trekName: '', 
        form: null,
        show(name, formElement) {
            this.trekName = name;
            this.form = formElement;
            this.open = true;
        },
        submit() {
            if (this.form) this.form.submit();
        }
    }"
    x-show="open"
    x-on:open-delete-modal.window="show($event.detail.name, $event.detail.form)"
    class="fixed inset-0 z-[100] overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
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
            class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden relative"
        >
            <div class="px-10 pt-10 pb-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Confirm Deletion</h3>
                <button @click="open = false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <div class="px-10 py-8">
                <p class="text-slate-600 font-medium leading-relaxed">
                    Are you sure you want to delete <strong class="text-slate-900" x-text="trekName"></strong>? 
                </p>
                <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-xl">
                    <p class="text-[10px] font-black text-red-800 uppercase tracking-widest leading-none">Warning</p>
                    <p class="text-xs font-semibold text-red-600 mt-2">This action is irreversible and will remove all associated departures.</p>
                </div>
            </div>

            <div class="px-10 py-8 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <button @click="open = false" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">
                    Cancel
                </button>
                <button @click="submit()" class="w-full sm:w-auto px-8 py-3 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all shadow-xl shadow-red-600/20">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle global click for delete buttons
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-confirm-trek]');
            if (!btn) return;

            e.preventDefault();
            const name = btn.dataset.confirmTrek;
            const form = btn.closest('form');

            window.dispatchEvent(new CustomEvent('open-delete-modal', { 
                detail: { name, form } 
            }));
        });
    });
</script>
