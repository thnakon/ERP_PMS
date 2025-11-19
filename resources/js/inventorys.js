document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Slider Toggle Logic ---
    const toggles = document.querySelectorAll('.inv-toggle-wrapper');

    toggles.forEach(toggleWrapper => {
        const buttons = toggleWrapper.querySelectorAll('.inv-toggle-btn');
        const activeButton = toggleWrapper.querySelector('.inv-toggle-btn.active');
        
        if (activeButton) updateSlider(toggleWrapper, activeButton);

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateSlider(toggleWrapper, btn);
                
                // Filter Logic for Product Page
                if (toggleWrapper.id === 'productViewToggle') {
                    applyProductFilter(btn.dataset.target);
                }
                
                // [!!! FIXED !!!] Filter Logic for Expiry Page
                if (toggleWrapper.id === 'expiryViewToggle') {
                    applyExpiryFilter(btn.dataset.target);
                }
            });
        });

        setTimeout(() => toggleWrapper.classList.add('slider-ready'), 100);
    });

    function updateSlider(wrapper, targetBtn) {
        const wrapperRect = wrapper.getBoundingClientRect();
        const btnRect = targetBtn.getBoundingClientRect();
        wrapper.style.setProperty('--slider-left', `${btnRect.left - wrapperRect.left}px`);
        wrapper.style.setProperty('--slider-width', `${btnRect.width}px`);
    }

    // --- 2. Filtering Logic ---
    
    // For Manage Products Page
    function applyProductFilter(filterType) {
        const sectionInactive = document.getElementById('section-inactive');
        if (sectionInactive) {
            if (filterType === 'active') {
                sectionInactive.classList.add('hidden');
            } else {
                sectionInactive.classList.remove('hidden');
            }
        }
    }

    // [!!! NEW !!!] For Expiry Management Page
    function applyExpiryFilter(filterType) {
        // Select all rows that are marked as filterable items
        const allRows = document.querySelectorAll('.product-row');
        
        allRows.forEach(row => {
            const rowStatus = row.dataset.status;

            if (filterType === 'all') {
                // Show everything
                row.classList.remove('hidden');
                row.style.display = 'grid'; // Ensure grid display is restored
            } else {
                // Show only matching status
                if (rowStatus === filterType) {
                    row.classList.remove('hidden');
                    row.style.display = 'grid';
                } else {
                    row.classList.add('hidden');
                    row.style.display = 'none';
                }
            }
        });
        
        // Optional: Manage section headers visibility (Simple version: just let them stay)
        // If you wanted to hide empty sections, you'd need more complex logic here.
    }

    // --- 3. Checkbox & Bulk Action Logic ---
    const selectAllBtn = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkState() {
        const checkedCount = document.querySelectorAll('.item-checkbox.checked').length;
        
        if (bulkActions && selectedCountSpan) {
            if (checkedCount > 0) {
                bulkActions.style.display = 'flex';
                selectedCountSpan.textContent = checkedCount;
            } else {
                bulkActions.style.display = 'none';
            }
        }
        
        if (selectAllBtn) {
            if (checkedCount === itemCheckboxes.length && itemCheckboxes.length > 0) {
                selectAllBtn.classList.add('checked');
            } else {
                selectAllBtn.classList.remove('checked');
            }
        }
    }

    itemCheckboxes.forEach(box => {
        box.addEventListener('click', (e) => {
            e.stopPropagation();
            box.classList.toggle('checked');
            
            const input = box.querySelector('input[type="checkbox"]');
            if(input) input.checked = box.classList.contains('checked');
            
            updateBulkState();
        });
    });

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', () => {
            const isChecked = selectAllBtn.classList.contains('checked');
            if (isChecked) {
                selectAllBtn.classList.remove('checked');
                itemCheckboxes.forEach(box => {
                    box.classList.remove('checked');
                    const input = box.querySelector('input[type="checkbox"]');
                    if(input) input.checked = false;
                });
            } else {
                selectAllBtn.classList.add('checked');
                itemCheckboxes.forEach(box => {
                    box.classList.add('checked');
                    const input = box.querySelector('input[type="checkbox"]');
                    if(input) input.checked = true;
                });
            }
            updateBulkState();
        });
    }

    // --- 4. Delete Confirmation ---
    const deleteBtns = document.querySelectorAll('.inv-icon-action.delete');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if(!confirm('Are you sure you want to remove this item?')) {
                e.preventDefault();
            }
        });
    });

});

// --- 6. Modal Functions (Global) ---
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
}

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
}

window.addEventListener('click', function(e) {
    if (e.target.classList.contains('inv-modal-overlay')) {
        e.target.classList.remove('show');
    }
});

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('inv-image-preview');
    const placeholder = document.getElementById('inv-image-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';      // โชว์รูป
            placeholder.style.display = 'none';   // ซ่อนข้อความ
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        // กรณีไม่ได้เลือกไฟล์ ให้กลับไปสถานะเดิม
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'block';
    }
}