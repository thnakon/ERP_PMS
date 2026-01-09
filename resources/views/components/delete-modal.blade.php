{{-- Delete Confirmation Modal --}}
<div id="delete-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeDeleteModal()">
    <div id="delete-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 24rem;"
        onclick="event.stopPropagation()">
        <div class="modal-content text-center">
            <div class="delete-modal-icon">
                <i class="ph-fill ph-trash text-2xl"></i>
            </div>
            <h3 id="delete-title" class="delete-modal-title">{{ __('delete_item_title') }}</h3>
            <p id="delete-desc" class="delete-modal-desc">
                {{ __('delete_item_confirm') }}
            </p>
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="modal-btn-cancel">
                    {{ __('cancel') }}
                </button>
                <button type="button" onclick="executeDelete()" class="modal-btn-danger">
                    {{ __('delete') }}
                </button>
            </div>
        </div>
    </div>
</div>
