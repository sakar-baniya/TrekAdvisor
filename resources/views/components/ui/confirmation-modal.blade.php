<!-- Global Confirmation Modal (Tailwind + Alpine) -->
<div 
    id="confirmModal"
    x-data="{ 
        open: false, 
        title: 'Confirm Action', 
        message: 'Are you sure?', 
        warning: '', 
        buttonText: 'Confirm',
        buttonClass: '',
        confirmCallback: null,
        configs: {
            'delete-trek': { title: 'Delete Trek', message: 'Are you sure you want to delete this trek?', warning: 'This action cannot be undone.', buttonText: 'Delete Trek', buttonClass: 'confirm-btn--danger' },
            'delete-review': { title: 'Delete Review', message: 'Are you sure you want to delete this review?', warning: 'This action cannot be undone.', buttonText: 'Delete Review', buttonClass: 'confirm-btn--danger' },
            'approve-hotel': { title: 'Approve Hotel', message: 'Are you sure you want to approve this hotel?', buttonText: 'Approve', buttonClass: 'confirm-btn--success' },
            'reject-hotel': { title: 'Set Hotel Inactive', message: 'Are you sure you want to deactivate this hotel?', warning: 'This change can be reversed anytime.', buttonText: 'Set Inactive', buttonClass: 'confirm-btn--danger' },
            'pending-hotel': { title: 'Move to Pending', message: 'Are you sure you want to move this hotel back to pending approval?', buttonText: 'Move to Pending', buttonClass: 'confirm-btn--warning' },
            'signout': { title: 'Sign Out', message: 'Are you sure you want to sign out?', buttonText: 'Sign Out', buttonClass: 'confirm-btn--secondary' },
            'save-departure': { title: 'Save Departure', message: 'Are you sure you want to save this departure?', buttonText: 'Save Departure', buttonClass: 'confirm-btn--success' },
            'update-departure': { title: 'Update Departure', message: 'Are you sure you want to update this departure?', buttonText: 'Update Departure', buttonClass: 'confirm-btn--success' },
            'create-trek': { title: 'Create Trek', message: 'Are you sure you want to create this trek?', buttonText: 'Create Trek', buttonClass: 'confirm-btn--success' },
            'update-trek': { title: 'Save Changes', message: 'Are you sure you want to save these changes?', buttonText: 'Save Changes', buttonClass: 'confirm-btn--success' },
            'create-user': { title: 'Create User', message: 'Are you sure you want to create this user?', buttonText: 'Create User', buttonClass: 'confirm-btn--success' },
            'approve-user': { title: 'Approve User', message: 'Are you sure you want to approve this user?', buttonText: 'Approve', buttonClass: 'confirm-btn--success' },
            'update-user': { title: 'Update User', message: 'Are you sure you want to update this user?', buttonText: 'Update User', buttonClass: 'confirm-btn--success' },
            'update-user-role': { title: 'Update User Role', message: 'Are you sure you want to change this user\'s role?', warning: 'This will change their access permissions.', buttonText: 'Update Role', buttonClass: 'confirm-btn--warning' },
            'delete-user': { title: 'Delete User', message: 'Are you sure you want to delete this user?', warning: 'This action cannot be undone.', buttonText: 'Delete User', buttonClass: 'confirm-btn--danger' },
            'process-payment': { title: 'Process Payment', message: 'Are you sure you want to process this payment?', warning: 'Please verify all payment details are correct.', buttonText: 'Process Payment', buttonClass: 'confirm-btn--success' },
            'refund-payment': { title: 'Refund Payment', message: 'Are you sure you want to refund this payment?', warning: 'This action will return funds to the customer.', buttonText: 'Refund Payment', buttonClass: 'confirm-btn--danger' },
            'update-booking-status': { title: 'Update Booking Status', message: 'Are you sure you want to update the booking status?', buttonText: 'Save Status', buttonClass: 'confirm-btn--success' }
        },
        show(config) {
            let options = {};
            
            // 1. Determine base config (either from preset or direct object)
            if (typeof config === 'string') {
                options = { ...(this.configs[config] || {}) };
            } else if (config.preset && this.configs[config.preset]) {
                options = { ...this.configs[config.preset], ...config };
            } else {
                options = { ...config };
            }

            // 2. Apply properties to state
            this.title = options.title || 'Confirm Action';
            this.message = options.message || 'Are you sure?';
            this.warning = options.warning || '';
            this.buttonText = options.buttonText || 'Confirm';
            this.buttonClass = options.buttonClass || '';
            
            // 3. Set the callback (prefer explicit callback, then form.submit)
            this.confirmCallback = options.callback || null;
            if (!this.confirmCallback && options.form) {
                this.confirmCallback = () => options.form.submit();
            }

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
                <h3 id="confirmTitle" x-text="title" class="text-xl font-semibold text-slate-900 tracking-tight"></h3>
                <button @click="open = false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <div class="px-10 py-8">
                <p id="confirmMessage" x-text="message" class="text-slate-600 font-medium leading-relaxed"></p>
                <div x-show="warning" class="mt-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl">
                    <p id="confirmWarning" x-text="warning" class="text-[10px] font-semibold text-amber-800 uppercase tracking-widest"></p>
                </div>
            </div>

            <div class="px-10 py-8 bg-slate-50/50 flex items-center justify-end gap-3">
                <button @click="open = false" class="px-8 py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">
                    Cancel
                </button>
                <button id="confirmActionBtn" @click="confirm()" :class="'px-8 py-3 text-white text-[10px] font-semibold uppercase tracking-widest rounded-xl transition-all shadow-xl ' + (buttonClass || 'bg-slate-900 hover:bg-slate-800 shadow-slate-900/20')" x-text="buttonText">
                    Confirm Action
                </button>
            </div>
        </div>
    </div>
</div>
