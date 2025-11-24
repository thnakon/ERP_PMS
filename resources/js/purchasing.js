document.addEventListener('DOMContentLoaded', () => {
    console.log('Purchasing JS Initialized');

    // ============================================================
    // PART 1: SUPPLIER PAGE LOGIC
    // ============================================================
    const supplierList = document.getElementById('supplier-list');

    if (supplierList) {
        // --- Elements ---
        const modalBackdrop = document.getElementById('supplier-modal-overlay');
        const modalForm = document.getElementById('supplier-form');
        const modalTitle = document.getElementById('modal-title');
        const saveBtn = document.getElementById('save-modal-btn');
        const closeBtn = document.getElementById('close-modal-btn');
        const cancelBtn = document.getElementById('cancel-modal-btn');
        const openBtn = document.getElementById('open-supplier-modal');
        const methodSpoof = document.getElementById('method-spoof');
        const formInputs = modalForm ? modalForm.querySelectorAll('input, textarea') : [];

        // --- Functions ---
        const openModal = () => {
            if (modalBackdrop) {
                modalBackdrop.classList.add('show');
            }
        };

        const closeModal = () => {
            if (modalBackdrop) {
                modalBackdrop.classList.remove('show');
            }
        };

        const setModalMode = (mode, data = {}) => {
            if (!modalForm) return;
            modalForm.reset();
            if (methodSpoof) methodSpoof.innerHTML = '';
            formInputs.forEach(input => input.disabled = false);
            if (saveBtn) saveBtn.style.display = 'block';

            if (mode === 'add') {
                if (modalTitle) modalTitle.textContent = 'Add New Supplier';
                if (saveBtn) saveBtn.textContent = 'Save Supplier';
                modalForm.action = "/purchasing/suppliers";
            } else if (mode === 'edit') {
                if (modalTitle) modalTitle.textContent = 'Edit Supplier';
                if (saveBtn) saveBtn.textContent = 'Update Supplier';
                modalForm.action = `/purchasing/suppliers/${data.id}`;
                if (methodSpoof) methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Fill Data
                if (document.getElementById('company_name')) document.getElementById('company_name').value = data.name || '';
                if (document.getElementById('contact_person')) document.getElementById('contact_person').value = data.contact_person || '';
                if (document.getElementById('phone')) document.getElementById('phone').value = data.phone || '';
                if (document.getElementById('email')) document.getElementById('email').value = data.email || '';
                if (document.getElementById('address')) document.getElementById('address').value = data.address || '';
            } else if (mode === 'view') {
                if (modalTitle) modalTitle.textContent = 'Supplier Details';
                formInputs.forEach(input => input.disabled = true);
                if (saveBtn) saveBtn.style.display = 'none';

                // Fill Data
                if (document.getElementById('company_name')) document.getElementById('company_name').value = data.name || '';
                if (document.getElementById('contact_person')) document.getElementById('contact_person').value = data.contact_person || '';
                if (document.getElementById('phone')) document.getElementById('phone').value = data.phone || '';
                if (document.getElementById('email')) document.getElementById('email').value = data.email || '';
                if (document.getElementById('address')) document.getElementById('address').value = data.address || '';
            }
        };

        // --- Event Listeners (Modal) ---
        if (openBtn) {
            openBtn.addEventListener('click', () => {
                setModalMode('add');
                openModal();
            });
        }
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', (e) => {
                if (e.target === modalBackdrop) closeModal();
            });
        }

        // --- Event Listeners (Row Actions) ---
        // Using event delegation on the list container for better performance and dynamic support
        supplierList.addEventListener('click', (e) => {
            // Handle Edit
            const editBtn = e.target.closest('.btn-edit');
            if (editBtn) {
                const data = JSON.parse(editBtn.dataset.supplier);
                setModalMode('edit', data);
                openModal();
                return;
            }

            // Handle View
            const viewBtn = e.target.closest('.btn-view');
            if (viewBtn) {
                const data = JSON.parse(viewBtn.dataset.supplier);
                setModalMode('view', data);
                openModal();
                return;
            }

            // Handle Delete
            const deleteBtn = e.target.closest('.btn-delete');
            if (deleteBtn) {
                e.preventDefault();
                const id = deleteBtn.dataset.id;
                if (deleteForm) {
                    deleteForm.action = `/purchasing/suppliers/${id}`;
                    if (deleteBackdrop) {
                        deleteBackdrop.classList.add('show');
                    }
                }
                return;
            }
        });

        // --- Delete Modal Logic ---
        const deleteBackdrop = document.getElementById('delete-modal-overlay');
        const deleteForm = document.getElementById('delete-form');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        const closeDeleteBtn = document.getElementById('close-delete-modal-btn');

        const closeDeleteModal = () => {
            if (deleteBackdrop) {
                deleteBackdrop.classList.remove('show');
            }
        };

        // Expose global function for blade onclick
        window.openDeleteModal = function (formId) {
            // If formId is passed (from blade), find that form. 
            // But here we have a single delete form in the modal that we update action for.
            // Wait, the blade implementation uses a per-row form for delete?
            // Let's check blade: <form action="..." id="delete-form-{{id}}"> ... onclick="openDeleteModal('delete-form-{{id}}')"
            // Ah, the blade uses individual forms. But my modal logic expects a single shared modal.
            // Let's adapt: The blade calls openDeleteModal with a form ID.
            // We should probably just submit that form.
            // BUT, we want a confirmation modal.
            // So, we need to intercept that.

            // Revised Strategy:
            // The blade has: onclick="openDeleteModal('delete-form-{{ $supplier->id }}')"
            // We need to store which form to submit.

            const targetForm = document.getElementById(formId);
            if (targetForm) {
                // Store reference to form
                window.targetDeleteForm = targetForm;
                if (deleteBackdrop) {
                    deleteBackdrop.style.display = 'flex';
                    void deleteBackdrop.offsetWidth;
                    deleteBackdrop.classList.add('is-open');
                }
            }
        };

        // If we have a shared delete form (like in my JS logic), we use that.
        // But the blade I wrote has individual forms.
        // Let's stick to the blade's individual forms and just trigger submit on confirmation.

        const confirmDeleteBtn = document.querySelector('#delete-modal-backdrop button[type="submit"]');
        // Wait, the modal in blade has a form inside it?
        // <div id="delete-modal-backdrop"> ... <form id="delete-form"> ...
        // My blade update had a single shared delete form in the modal.
        // BUT the list view had individual forms.
        // I should fix the list view to NOT have forms, but just buttons that open the shared modal.
        // OR, make the shared modal submit the individual form.

        // Let's use the shared modal approach properly.
        // The JS below handles the shared modal action update.

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // If using the JS listener instead of onclick
                e.preventDefault();
                const id = btn.dataset.id;
                if (deleteForm) {
                    deleteForm.action = `/purchasing/suppliers/${id}`;
                    if (deleteBackdrop) {
                        deleteBackdrop.style.display = 'flex';
                        void deleteBackdrop.offsetWidth;
                        deleteBackdrop.classList.add('is-open');
                    }
                }
            });
        });

        if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);
        if (deleteBackdrop) {
            deleteBackdrop.addEventListener('click', (e) => {
                if (e.target === deleteBackdrop) closeDeleteModal();
            });
        }

        // --- Bulk Actions Logic ---
        const selectAll = document.getElementById('select-all-checkbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        const bulkDeleteBtn = document.getElementById('btn-bulk-delete');

        const updateBulkUI = () => {
            const checked = document.querySelectorAll('.item-checkbox.active');
            const count = checked.length;
            if (selectedCount) selectedCount.textContent = count;
            if (bulkActions) bulkActions.style.display = count > 0 ? 'flex' : 'none';

            if (selectAll) {
                if (count === checkboxes.length && count > 0) selectAll.classList.add('active');
                else selectAll.classList.remove('active');
            }
        };

        // Custom Checkbox Handling
        const toggleCheckbox = (el) => {
            el.classList.toggle('active');
            // Toggle row selection style
            const row = el.closest('.purchasing-list-row');
            if (row) {
                if (el.classList.contains('active')) row.classList.add('selected-row');
                else row.classList.remove('selected-row');
            }
        };

        checkboxes.forEach(cb => {
            cb.addEventListener('click', () => {
                toggleCheckbox(cb);
                updateBulkUI();
            });
        });

        if (selectAll) {
            selectAll.addEventListener('click', () => {
                const isActive = selectAll.classList.contains('active');
                // Toggle all
                if (isActive) {
                    // Uncheck all
                    selectAll.classList.remove('active');
                    checkboxes.forEach(cb => {
                        cb.classList.remove('active');
                        cb.closest('.purchasing-list-row')?.classList.remove('selected-row');
                    });
                } else {
                    // Check all
                    selectAll.classList.add('active');
                    checkboxes.forEach(cb => {
                        cb.classList.add('active');
                        cb.closest('.purchasing-list-row')?.classList.add('selected-row');
                    });
                }
                updateBulkUI();
            });
        }

        // Bulk Delete Action
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', () => {
                const checked = document.querySelectorAll('.item-checkbox.active');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                if (ids.length === 0) return;

                if (confirm(`Are you sure you want to delete ${ids.length} suppliers?`)) {
                    fetch('/purchasing/suppliers/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(err => console.error(err));
                }
            });
        }
    }

    // ============================================================
    // PART 2: PO PAGE LOGIC (SLIDER, MODAL, SEARCH)
    // ============================================================

    // --- Sliding Filter Logic ---
    const sliderContainer = document.querySelector('.sliding-toggle-filter');
    if (sliderContainer) {
        const updateSlider = () => {
            const activeBtn = sliderContainer.querySelector('.toggle-btn.active');
            if (activeBtn) {
                const rect = activeBtn.getBoundingClientRect();
                const containerRect = sliderContainer.getBoundingClientRect();
                const left = rect.left - containerRect.left;
                const width = rect.width;

                sliderContainer.style.setProperty('--slider-left', `${left}px`);
                sliderContainer.style.setProperty('--slider-width', `${width}px`);
            }
        };

        // Initial update
        // We need a slight delay to ensure fonts are loaded and layout is stable
        setTimeout(updateSlider, 100);
        window.addEventListener('resize', updateSlider);

        // Update on click (though page reload happens, this gives instant feedback)
        sliderContainer.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                sliderContainer.querySelector('.active')?.classList.remove('active');
                btn.classList.add('active');
                updateSlider();
            });
        });
    }

    const poListContainer = document.getElementById('po-list-container');

    // --- 2.1 PO Modal Logic ---
    const poModalOverlay = document.getElementById('po-modal-overlay');
    const poForm = document.getElementById('po-form');
    const openPoBtn = document.getElementById('open-po-modal');
    const closePoBtn = document.getElementById('close-po-modal-btn');
    const cancelPoBtn = document.getElementById('cancel-po-modal-btn');
    const poItemsBody = document.getElementById('po-items-body');
    const addItemBtn = document.getElementById('add-item-btn');
    const poTotalDisplay = document.getElementById('po-total-display');
    const poModalTitle = document.getElementById('po-modal-title');
    const poSaveBtn = document.getElementById('save-po-btn');
    const poMethodSpoof = document.getElementById('po-method-spoof');

    const openPoModal = () => {
        if (poModalOverlay) poModalOverlay.classList.add('show');
    };

    const closePoModal = () => {
        if (poModalOverlay) poModalOverlay.classList.remove('show');
    };

    if (openPoBtn) {
        openPoBtn.addEventListener('click', () => {
            resetPoModal();
            openPoModal();
        });
    }
    if (closePoBtn) closePoBtn.addEventListener('click', closePoModal);
    if (cancelPoBtn) cancelPoBtn.addEventListener('click', closePoModal);
    if (poModalOverlay) {
        poModalOverlay.addEventListener('click', (e) => {
            if (e.target === poModalOverlay) closePoModal();
        });
    }

    // --- 2.2 Dynamic Items Logic ---
    let itemIndex = 0;

    const calculatePoTotal = () => {
        let total = 0;
        const rows = poItemsBody.querySelectorAll('tr');
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            total += qty * cost;
        });
        if (poTotalDisplay) poTotalDisplay.textContent = '฿' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const addPoItemRow = (data = null) => {
        if (!poItemsBody) return;

        const products = window.productsData || [];
        const tr = document.createElement('tr');
        tr.classList.add('po-item-row');

        const productId = data ? data.product_id : '';
        const qty = data ? data.quantity : 1;
        const cost = data ? data.cost_price : 0;

        let options = '<option value="" disabled selected>Select Product</option>';
        products.forEach(p => {
            const selected = p.id == productId ? 'selected' : '';
            options += `<option value="${p.id}" data-price="${p.price}" ${selected}>${p.name}</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="inv-form-input product-select" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="inv-form-input qty-input" value="${qty}" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][cost_price]" class="inv-form-input cost-input" value="${cost}" min="0" step="0.01" required>
            </td>
            <td style="text-align: center;">
                <button type="button" class="po-remove-btn"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        poItemsBody.appendChild(tr);
        itemIndex++;

        // Event Listeners for this row
        const productSelect = tr.querySelector('.product-select');
        const qtyInput = tr.querySelector('.qty-input');
        const costInput = tr.querySelector('.cost-input');
        const removeBtn = tr.querySelector('.po-remove-btn');

        productSelect.addEventListener('change', (e) => {
            // Auto-fill cost if new row (optional logic)
            // const price = e.target.selectedOptions[0].dataset.price;
            // if (costInput.value == 0) costInput.value = price * 0.7; // Estimate cost
            calculatePoTotal();
        });

        qtyInput.addEventListener('input', calculatePoTotal);
        costInput.addEventListener('input', calculatePoTotal);
        removeBtn.addEventListener('click', () => {
            tr.remove();
            calculatePoTotal();
        });

        calculatePoTotal();
    };

    if (addItemBtn) {
        addItemBtn.addEventListener('click', () => addPoItemRow());
    }

    const resetPoModal = () => {
        if (poForm) poForm.reset();
        if (poItemsBody) poItemsBody.innerHTML = '';
        if (poTotalDisplay) poTotalDisplay.textContent = '฿0.00';
        if (poModalTitle) poModalTitle.textContent = 'Create New PO';
        if (poSaveBtn) poSaveBtn.textContent = 'Save Purchase Order';
        if (poMethodSpoof) poMethodSpoof.innerHTML = '';
        if (poForm) poForm.action = "/purchasing/purchase-orders";

        // Add one empty row
        addPoItemRow();

        // Set default date
        const dateInput = document.getElementById('po_date');
        if (dateInput) dateInput.valueAsDate = new Date();
    };

    // --- 2.3 Edit PO Logic ---
    const setPoEditMode = (po, items) => {
        resetPoModal();
        if (poModalTitle) poModalTitle.textContent = 'Edit Purchase Order';
        if (poSaveBtn) poSaveBtn.textContent = 'Update Purchase Order';
        if (poForm) poForm.action = `/purchasing/purchase-orders/${po.id}`;
        if (poMethodSpoof) poMethodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Fill Fields
        if (document.getElementById('po_supplier')) document.getElementById('po_supplier').value = po.supplier_id;
        if (document.getElementById('po_date')) document.getElementById('po_date').value = po.purchase_date.split('T')[0];
        if (document.getElementById('po_status')) document.getElementById('po_status').value = po.status;

        // Fill Items
        if (poItemsBody) poItemsBody.innerHTML = ''; // Clear default row
        items.forEach(item => {
            addPoItemRow(item);
        });
        calculatePoTotal();
    };

    if (poListContainer) {
        poListContainer.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.btn-edit-po');
            if (editBtn) {
                const po = JSON.parse(editBtn.dataset.po);
                const items = JSON.parse(editBtn.dataset.items);
                setPoEditMode(po, items);
                openPoModal();
            }

            const viewBtn = e.target.closest('.btn-view-po');
            if (viewBtn) {
                // For now, view is same as edit but maybe disabled?
                // Or just show edit modal. User asked for View/Edit.
                const po = JSON.parse(viewBtn.dataset.po);
                const items = JSON.parse(viewBtn.dataset.items);
                setPoEditMode(po, items);
                openPoModal();
                // Disable inputs for view mode if needed
                // poForm.querySelectorAll('input, select, button').forEach(el => {
                //    if(el.id !== 'close-po-modal-btn' && el.id !== 'cancel-po-modal-btn') el.disabled = true;
                // });
            }
        });
    }

    // --- 2.4 Delete PO Logic ---
    const deletePoOverlay = document.getElementById('delete-po-modal-overlay');
    const deletePoForm = document.getElementById('delete-po-form');
    const closeDeletePoBtn = document.getElementById('close-delete-po-modal-btn');
    const cancelDeletePoBtn = document.getElementById('cancel-delete-po-btn');

    const closeDeletePoModal = () => {
        if (deletePoOverlay) deletePoOverlay.classList.remove('show');
    };

    if (poListContainer) {
        poListContainer.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.btn-delete-po');
            if (deleteBtn) {
                const id = deleteBtn.dataset.id;
                if (deletePoForm) deletePoForm.action = `/purchasing/purchase-orders/${id}`;
                if (deletePoOverlay) deletePoOverlay.classList.add('show');
            }
        });
    }

    if (closeDeletePoBtn) closeDeletePoBtn.addEventListener('click', closeDeletePoModal);
    if (cancelDeletePoBtn) cancelDeletePoBtn.addEventListener('click', closeDeletePoModal);
    if (deletePoOverlay) {
        deletePoOverlay.addEventListener('click', (e) => {
            if (e.target === deletePoOverlay) closeDeletePoModal();
        });
    }

    // --- 2.5 Bulk Delete PO ---
    const bulkDeletePoBtn = document.getElementById('bulk-delete-btn');
    if (bulkDeletePoBtn) {
        bulkDeletePoBtn.addEventListener('click', () => {
            const checked = document.querySelectorAll('.item-checkbox.active');
            const ids = Array.from(checked).map(cb => cb.dataset.id);

            if (ids.length === 0) return;

            if (confirm(`Are you sure you want to delete ${ids.length} orders?`)) {
                fetch('/purchasing/purchase-orders/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ ids: ids })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error(err));
            }
        });
    }

    // ============================================================
    // PART 3: GOODS RECEIVED (GR) LOGIC
    // ============================================================

    // --- 3.1 GR Modal ---
    const grBackdrop = document.getElementById('gr-modal-backdrop');
    const openGrBtn = document.getElementById('open-gr-modal');
    const closeGrBtn = document.getElementById('close-gr-modal-btn');
    const cancelGrBtn = document.getElementById('cancel-gr-modal-btn');
    const grForm = document.getElementById('create-gr-form');

    const openGrModal = () => {
        if (grBackdrop) {
            grBackdrop.style.display = 'flex';
            const grDate = document.getElementById('gr_date');
            if (grDate && !grDate.value) grDate.valueAsDate = new Date();
            setTimeout(() => grBackdrop.classList.add('is-open'), 10);
        }
    };

    const closeGrModal = () => {
        if (grBackdrop) {
            grBackdrop.classList.remove('is-open');
            setTimeout(() => {
                grBackdrop.style.display = 'none';
                if (grForm) grForm.reset();
            }, 350);
        }
    };

    if (openGrBtn) openGrBtn.addEventListener('click', openGrModal);
    if (closeGrBtn) closeGrBtn.addEventListener('click', closeGrModal);
    if (cancelGrBtn) cancelGrBtn.addEventListener('click', closeGrModal);
    if (grBackdrop) grBackdrop.addEventListener('click', (e) => { if (e.target === grBackdrop) closeGrModal(); });

    // --- 3.2 View Toggles ---
    const poSearchView = document.getElementById('po-search-view');
    const poReceiveView = document.getElementById('po-receive-view');
    const searchPoBtn = document.getElementById('search-po-btn');
    const backToSearchBtn = document.getElementById('back-to-search-btn');

    if (searchPoBtn) {
        searchPoBtn.addEventListener('click', () => {
            if (poReceiveView && poSearchView) {
                poSearchView.style.display = 'none';
                poReceiveView.style.display = 'block';
            }
        });
    }
    if (backToSearchBtn) {
        backToSearchBtn.addEventListener('click', () => {
            if (poReceiveView && poSearchView) {
                poSearchView.style.display = 'block';
                poReceiveView.style.display = 'none';
            }
        });
    }
});
