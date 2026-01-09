{{-- Standard Modal --}}
<div id="modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="toggleModal(false)">
    <div id="modal-panel" class="modal-panel modal-panel-hidden" onclick="event.stopPropagation()">
        <div class="text-center">
            <div class="modal-icon modal-icon-blue">
                <i class="ph-fill ph-shield-check text-3xl"></i>
            </div>
            <h3 id="modal-title" class="modal-title">{{ __('modal.action_required') }}</h3>
            <p id="modal-desc" class="modal-desc">
                {{ __('modal.default_message') }}
            </p>
            <div class="modal-actions">
                <button onclick="toggleModal(false)" class="modal-btn-cancel">
                    {{ __('general.cancel') }}
                </button>
                <button onclick="toggleModal(false); showToast('{{ __('general.confirmed') }}', 'success')"
                    id="modal-confirm-btn" class="modal-btn-confirm">
                    {{ __('general.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
