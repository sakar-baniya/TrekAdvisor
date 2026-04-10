<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal" style="display: none;">
    <div class="confirm-modal__overlay"></div>
    <div class="confirm-modal__body">
        <div class="confirm-modal__header">
            <h3 id="confirmTitle">Confirm Action</h3>
            <button type="button" class="confirm-modal__close" onclick="closeConfirmModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="confirm-modal__content">
            <p id="confirmMessage">Are you sure?</p>
            <p id="confirmWarning" class="confirm-modal__warning" style="display: none;"></p>
        </div>
        <div class="confirm-modal__footer">
            <button type="button" class="confirm-btn confirm-btn--secondary" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="confirm-btn" id="confirmActionBtn">Confirm</button>
        </div>
    </div>
</div>
