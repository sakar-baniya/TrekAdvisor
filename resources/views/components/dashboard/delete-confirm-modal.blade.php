{{-- Delete Confirmation Modal --}}
<div id="delete-confirm-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Delete Trek</h3>
            <button type="button" class="modal-close" onclick="document.getElementById('delete-confirm-modal').style.display = 'none'" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong id="trek-name-display"></strong>?</p>
            <p style="color: var(--u-gray-600); font-size: 0.875rem; margin-top: 0.5rem;">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="u-btn u-btn--secondary" onclick="document.getElementById('delete-confirm-modal').style.display = 'none'">Cancel</button>
            <button type="button" class="u-btn u-btn--danger" id="confirm-delete-btn">Delete Trek</button>
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
        max-width: 400px;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('delete-confirm-modal');
        const confirmBtn = document.getElementById('confirm-delete-btn');
        let pendingForm = null;

        // Handle delete button clicks
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-confirm-trek]');
            if (!btn) return;

            e.preventDefault();
            const trekName = btn.dataset.confirmTrek;
            pendingForm = btn.closest('form');

            document.getElementById('trek-name-display').textContent = trekName;
            modal.style.display = 'flex';
        });

        // Handle confirm delete
        confirmBtn.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        // Close modal on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display !== 'none') {
                modal.style.display = 'none';
            }
        });
    });
</script>
