{{-- Universal Action Confirmation Modal (Tailwind + Alpine) --}}
<div 
    x-data="{ 
        open: false, 
        title: 'Confirm Action', 
        message: 'Are you sure?', 
        warning: '', 
        buttonText: 'Confirm',
        buttonClass: 'bg-slate-900',
        confirmCallback: null,

        show(config) {
            this.title = config.title || 'Confirm Action';
            this.message = config.message || 'Are you sure?';
            this.warning = config.warning || '';
            this.buttonText = config.buttonText || 'Confirm';
            this.buttonClass = config.buttonClass || 'bg-slate-900';
            this.confirmCallback = config.onConfirm;
            this.open = true;
        },
        confirm() {
            if (this.confirmCallback) this.confirmCallback();
            this.open = false;
        }
    }"
    x-show="open"
    x-on:action-confirm.window="show($event.detail)"
    class="fixed inset-0 z-[100] overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            x-show="open" x-transition.scale.95
            class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden relative"
        >
            <div class="px-10 pt-10 pb-6 border-b border-slate-50 flex items-center justify-between">
                <h3 x-text="title" class="text-xl font-black text-slate-900 tracking-tight"></h3>
                <button @click="open = false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <div class="px-10 py-8">
                <p x-text="message" class="text-slate-600 font-medium leading-relaxed"></p>
                <div x-show="warning" class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl">
                    <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest leading-none">Important</p>
                    <p x-text="warning" class="text-xs font-semibold text-amber-700 mt-2"></p>
                </div>
            </div>

            <div class="px-10 py-8 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <button @click="open = false" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">
                    Cancel
                </button>
                <button 
                    @click="confirm()" 
                    :class="buttonClass"
                    class="w-full sm:w-auto px-8 py-3 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl"
                    x-text="buttonText"
                ></button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-confirm-action]');
            if (!btn) return;

            e.preventDefault();
            const form = btn.closest('form');
            const actionType = btn.dataset.confirmAction;

            let options = {
                title: 'Confirm',
                message: 'Are you sure?',
                buttonText: 'Confirm',
                buttonClass: 'bg-slate-900 hover:bg-slate-800 shadow-slate-900/20',
                onConfirm: () => form && form.submit()
            };

            switch (actionType) {
                case 'delete':
                case 'delete-review':
                    options = {
                        ...options,
                        title: 'Confirm Deletion',
                        message: 'Permanently remove this item from the platform?',
                        warning: 'This action cannot be undone.',
                        buttonText: 'Delete Now',
                        buttonClass: 'bg-red-600 hover:bg-red-700 shadow-red-600/20'
                    };
                    break;
                case 'approve-hotel':
                    options = {
                        ...options,
                        title: 'Approve Hotel',
                        message: btn.dataset.confirmMessage || 'Ready to set this hotel as active?',
                        buttonText: 'Approve Stay',
                        buttonClass: 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20'
                    };
                    break;
                case 'reject-hotel':
                    options = {
                        ...options,
                        title: 'Set Inactive',
                        message: 'Deactivate this property listing?',
                        buttonText: 'Deactivate',
                        buttonClass: 'bg-slate-900 hover:bg-slate-800 shadow-slate-900/20'
                    };
                    break;
                case 'signout':
                    options = {
                        ...options,
                        title: 'Sign Out',
                        message: 'End your current session?',
                        buttonText: 'Sign Out',
                        buttonClass: 'bg-slate-900 hover:bg-slate-800 shadow-slate-900/20'
                    };
                    break;
            }

            window.dispatchEvent(new CustomEvent('action-confirm', { detail: options }));
        });
    });
</script>
