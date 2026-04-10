{{-- Universal Action Confirmation Modal --}}
<div id="action-confirm-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Confirm Action</h3>
            <button type="button" class="modal-close" onclick="closeActionModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="modal-message">Are you sure?</p>
            <p id="modal-warning" style="color: var(--u-gray-600); font-size: 0.875rem; margin-top: 0.5rem; display: none;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="u-btn u-btn--secondary" onclick="closeActionModal()">Cancel</button>
            <button type="button" class="u-btn" id="confirm-action-btn">Confirm</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 420px;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid var(--u-border-color, #e0e0e0);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--u-navy, #1a1a1a);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--u-gray-600, #666);
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .modal-close:hover {
        background-color: var(--u-gray-100, #f5f5f5);
    }

    .modal-body {
        padding: 1.5rem;
        color: var(--u-gray-700, #555);
        line-height: 1.6;
    }

    .modal-body p {
        margin: 0;
    }

    .modal-footer {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding: 1.5rem;
        border-top: 1px solid var(--u-border-color, #e0e0e0);
    }

    .u-btn--danger {
        --btn-bg: #dc3545;
        --btn-hover-bg: #c82333;
        --btn-text: white;
    }

    .u-btn--warning {
        --btn-bg: #ff9800;
        --btn-hover-bg: #e68900;
        --btn-text: white;
    }

    .u-btn--success {
        --btn-bg: #28a745;
        --btn-hover-bg: #218838;
        --btn-text: white;
    }
</style>

<script>
    (() => {
        const modal = document.getElementById('action-confirm-modal');
        const confirmBtn = document.getElementById('confirm-action-btn');
        const titleEl = document.getElementById('modal-title');
        const messageEl = document.getElementById('modal-message');
        const warningEl = document.getElementById('modal-warning');

        let pendingAction = null;

        // Show modal for actions
        window.showActionConfirmation = (options) => {
            const {
                title = 'Confirm Action',
                message = 'Are you sure?',
                warning = '',
                buttonText = 'Confirm',
                buttonClass = '',
                onConfirm = () => {}
            } = options;

            titleEl.textContent = title;
            messageEl.textContent = message;

            if (warning) {
                warningEl.textContent = warning;
                warningEl.style.display = 'block';
            } else {
                warningEl.style.display = 'none';
            }

            confirmBtn.textContent = buttonText;
            confirmBtn.className = 'u-btn ' + buttonClass;

            pendingAction = onConfirm;
            modal.style.display = 'flex';
        };

        // Close modal
        window.closeActionModal = () => {
            modal.style.display = 'none';
            pendingAction = null;
        };

        // Confirm action
        confirmBtn.addEventListener('click', () => {
            if (pendingAction) {
                pendingAction();
            }
            closeActionModal();
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeActionModal();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display !== 'none') {
                closeActionModal();
            }
        });

        // Handle data-confirm attributes for forms
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
                buttonClass: '',
                onConfirm: () => form && form.submit()
            };

            // Different action types with custom messages
            switch (actionType) {
                case 'delete':
                    options = {
                        ...options,
                        title: 'Delete Item',
                        message: 'Are you sure you want to delete this item?',
                        warning: 'This action cannot be undone.',
                        buttonText: 'Delete',
                        buttonClass: 'u-btn--danger'
                    };
                    break;
                case 'delete-review':
                    options = {
                        ...options,
                        title: 'Delete Review',
                        message: 'Are you sure you want to delete this review?',
                        warning: 'This action cannot be undone.',
                        buttonText: 'Delete Review',
                        buttonClass: 'u-btn--danger'
                    };
                    break;
                case 'approve-hotel':
                    options = {
                        ...options,
                        title: 'Approve Hotel',
                        message: btn.dataset.confirmMessage || 'Are you sure you want to approve this hotel?',
                        buttonText: 'Approve',
                        buttonClass: 'u-btn--success'
                    };
                    break;
                case 'reject-hotel':
                    options = {
                        ...options,
                        title: 'Set Hotel Inactive',
                        message: 'Are you sure you want to deactivate this hotel?',
                        warning: 'Existing bookings will not be affected.',
                        buttonText: 'Set Inactive',
                        buttonClass: 'u-btn--danger'
                    };
                    break;
                case 'pending-hotel':
                    options = {
                        ...options,
                        title: 'Move to Pending',
                        message: 'Are you sure you want to move this hotel back to pending?',
                        buttonText: 'Move to Pending',
                        buttonClass: 'u-btn--warning'
                    };
                    break;
                case 'signout':
                    options = {
                        ...options,
                        title: 'Sign Out',
                        message: 'Are you sure you want to sign out?',
                        buttonText: 'Sign Out',
                        buttonClass: 'u-btn--secondary'
                    };
                    break;
            }

            showActionConfirmation(options);
        });
    })();
</script>
