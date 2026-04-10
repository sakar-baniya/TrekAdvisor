/**
 * Confirmation Modal System
 * Handles all confirmation dialogs across the application
 */

const ConfirmationModal = (() => {
    const modal = document.getElementById('confirmModal');
    const title = document.getElementById('confirmTitle');
    const message = document.getElementById('confirmMessage');
    const warning = document.getElementById('confirmWarning');
    const actionBtn = document.getElementById('confirmActionBtn');
    const overlay = document.querySelector('.confirm-modal__overlay');

    let pendingForm = null;

    // Configuration for different action types
    const actionConfigs = {
        'delete-trek': {
            title: 'Delete Trek',
            message: 'Are you sure you want to delete this trek?',
            warning: 'This action cannot be undone.',
            buttonText: 'Delete Trek',
            buttonClass: 'confirm-btn--danger'
        },
        'delete-review': {
            title: 'Delete Review',
            message: 'Are you sure you want to delete this review?',
            warning: 'This action cannot be undone.',
            buttonText: 'Delete Review',
            buttonClass: 'confirm-btn--danger'
        },
        'approve-hotel': {
            title: 'Approve Hotel',
            message: 'Are you sure you want to approve this hotel?',
            buttonText: 'Approve',
            buttonClass: 'confirm-btn--success'
        },
        'reject-hotel': {
            title: 'Set Hotel Inactive',
            message: 'Are you sure you want to deactivate this hotel?',
            warning: 'This change can be reversed anytime.',
            buttonText: 'Set Inactive',
            buttonClass: 'confirm-btn--danger'
        },
        'pending-hotel': {
            title: 'Move to Pending',
            message: 'Are you sure you want to move this hotel back to pending approval?',
            buttonText: 'Move to Pending',
            buttonClass: 'confirm-btn--warning'
        },
        'signout': {
            title: 'Sign Out',
            message: 'Are you sure you want to sign out?',
            buttonText: 'Sign Out',
            buttonClass: 'confirm-btn--secondary'
        },
        'save-departure': {
            title: 'Save Departure',
            message: 'Are you sure you want to save this departure?',
            buttonText: 'Save Departure',
            buttonClass: 'confirm-btn--success'
        },
        'update-departure': {
            title: 'Update Departure',
            message: 'Are you sure you want to update this departure?',
            buttonText: 'Update Departure',
            buttonClass: 'confirm-btn--success'
        },
        'create-trek': {
            title: 'Create Trek',
            message: 'Are you sure you want to create this trek?',
            buttonText: 'Create Trek',
            buttonClass: 'confirm-btn--success'
        },
        'update-trek': {
            title: 'Save Changes',
            message: 'Are you sure you want to save these changes?',
            buttonText: 'Save Changes',
            buttonClass: 'confirm-btn--success'
        },
        'create-user': {
            title: 'Create User',
            message: 'Are you sure you want to create this user?',
            buttonText: 'Create User',
            buttonClass: 'confirm-btn--success'
        },
        'approve-user': {
            title: 'Approve User',
            message: 'Are you sure you want to approve this user?',
            buttonText: 'Approve',
            buttonClass: 'confirm-btn--success'
        },
        'update-user': {
            title: 'Update User',
            message: 'Are you sure you want to update this user?',
            buttonText: 'Update User',
            buttonClass: 'confirm-btn--success'
        },
        'update-user-role': {
            title: 'Update User Role',
            message: 'Are you sure you want to change this user\'s role?',
            warning: 'This will change their access permissions.',
            buttonText: 'Update Role',
            buttonClass: 'confirm-btn--warning'
        },
        'delete-user': {
            title: 'Delete User',
            message: 'Are you sure you want to delete this user?',
            warning: 'This action cannot be undone.',
            buttonText: 'Delete User',
            buttonClass: 'confirm-btn--danger'
        },
        'process-payment': {
            title: 'Process Payment',
            message: 'Are you sure you want to process this payment?',
            warning: 'Please verify all payment details are correct.',
            buttonText: 'Process Payment',
            buttonClass: 'confirm-btn--success'
        },
        'refund-payment': {
            title: 'Refund Payment',
            message: 'Are you sure you want to refund this payment?',
            warning: 'This action will return funds to the customer.',
            buttonText: 'Refund Payment',
            buttonClass: 'confirm-btn--danger'
        },
        'flag-review': {
            title: 'Flag Review',
            message: 'Are you sure you want to flag this review?',
            buttonText: 'Flag Review',
            buttonClass: 'confirm-btn--warning'
        },
        'unflag-review': {
            title: 'Remove Flag',
            message: 'Are you sure you want to remove the flag from this review?',
            buttonText: 'Remove Flag',
            buttonClass: 'confirm-btn--success'
        },
        'update-booking-status': {
            title: 'Update Booking Status',
            message: 'Are you sure you want to update the booking status?',
            buttonText: 'Save Status',
            buttonClass: 'confirm-btn--success'
        }
    };

    const show = (options = {}) => {
        pendingForm = options.form || null;

        title.textContent = options.title || 'Confirm Action';
        message.textContent = options.message || 'Are you sure?';

        if (options.warning) {
            warning.textContent = options.warning;
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }

        actionBtn.textContent = options.buttonText || 'Confirm';
        actionBtn.className = `confirm-btn ${options.buttonClass || ''}`;
        actionBtn.onclick = options.onConfirm || (() => {
            if (pendingForm) pendingForm.submit();
        });

        modal.style.display = 'flex';
    };

    const close = () => {
        modal.style.display = 'none';
        pendingForm = null;
    };

    const handleDataConfirmClick = (e) => {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;

        e.preventDefault();
        const form = btn.closest('form');
        const actionType = btn.dataset.confirm;
        const config = actionConfigs[actionType];

        if (!config) return;

        show({
            ...config,
            form,
            onConfirm: () => form && form.submit()
        });
    };

    const handleFormSubmit = (e) => {
        const confirmTrigger = e.target.querySelector('[data-confirm]');
        if (!confirmTrigger) {
            return;
        }

        const actionType = confirmTrigger.dataset.confirm;
        const config = actionConfigs[actionType];

        if (!config) {
            return;
        }

        e.preventDefault();
        show({
            ...config,
            form: e.target,
            onConfirm: () => e.target.submit()
        });
    };

    // Event listeners
    const init = () => {
        document.addEventListener('click', handleDataConfirmClick);
        document.addEventListener('submit', handleFormSubmit, true);
        overlay?.addEventListener('click', close);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display !== 'none') {
                close();
            }
        });
    };

    // Public API
    return {
        show,
        close,
        init
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ConfirmationModal.init());
} else {
    ConfirmationModal.init();
}

// Expose to window for inline calls if needed
window.showConfirm = ConfirmationModal.show;
window.closeConfirmModal = ConfirmationModal.close;
