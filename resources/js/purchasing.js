document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // PART 1: SUPPLIER PAGE LOGIC (MODAL, CRUD, MOCK DATA)
    // ============================================================

    const backdrop = document.getElementById('supplier-modal-backdrop');
    const openModalBtn = document.getElementById('open-supplier-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const modalForm = document.getElementById('supplier-form');
    
    // Get input elements and buttons
    const modalTitle = document.getElementById('modal-title');
    const saveBtn = document.getElementById('save-modal-btn');
    const formInputs = modalForm ? modalForm.querySelectorAll('input, textarea') : [];

    // --- 1.1 Mock Data Fetcher ---
    const fetchSupplierData = (id) => {
        const data = {
            '1': {
                company_name: 'บริษัท ยาดี จำกัด',
                tax_id: '1234567890123',
                contact_person: 'คุณสมชาย',
                phone: '081-234-5678',
                email: 'contact@yad.co.th',
                address: '125/7 ถนนสุขุมวิท กรุงเทพฯ 10110',
                notes: 'ส่งของทุกวันอังคาร, ขั้นต่ำ 5,000 บาท',
            },
            '2': {
                company_name: 'Pharma Distribution Co., Ltd.',
                tax_id: '9876543210987',
                contact_person: 'คุณสุนีย์',
                phone: '02-999-8888',
                email: 'sunee@pharma-dist.com',
                address: '88/10 ถนนพระราม 9 กรุงเทพฯ 10310',
                notes: 'นโยบายคืนสินค้า 7 วัน',
            },
            '3': {
                company_name: 'MedSupply Thailand',
                tax_id: '5551112223330',
                contact_person: 'คุณวสันต์',
                phone: '090-123-4567',
                email: 'contact@medsupply.th',
                address: '108 สุขสวัสดิ์ พระประแดง สมุทรปราการ',
                notes: 'เน้นผลิตภัณฑ์นำเข้าจากยุโรป',
            },
            '4': {
                company_name: 'Bangkok Drugs Import',
                tax_id: '4445556667770',
                contact_person: 'Dr. Aree',
                phone: '02-100-2000',
                email: 'aree@bdi.co.th',
                address: '50/5 ถนนเพชรบุรีตัดใหม่',
                notes: 'เป็นผู้จำหน่ายรายใหญ่ของวัคซีน',
            },
            '5': {
                company_name: 'Pure Health Corp.',
                tax_id: '3332221110000',
                contact_person: 'Ms. Joy',
                phone: '089-000-1111',
                email: 'joy@purehealth.net',
                address: '99/99 ถนนรัชดาภิเษก',
                notes: 'ไม่มีขั้นต่ำในการสั่งซื้อ',
            }
        };
        return data[id] || {};
    };

    // --- 1.2 Helper: Fill Form ---
    const fillForm = (data) => {
        if (data) {
            if(document.getElementById('company_name')) document.getElementById('company_name').value = data.company_name || '';
            if(document.getElementById('tax_id')) document.getElementById('tax_id').value = data.tax_id || '';
            if(document.getElementById('contact_person')) document.getElementById('contact_person').value = data.contact_person || '';
            if(document.getElementById('phone')) document.getElementById('phone').value = data.phone || '';
            if(document.getElementById('email')) document.getElementById('email').value = data.email || '';
            if(document.getElementById('address')) document.getElementById('address').value = data.address || '';
            if(document.getElementById('notes')) document.getElementById('notes').value = data.notes || '';
        }
    }

    // --- 1.3 Helper: Set Modal Mode (Add/Edit/View) ---
    const setModalMode = (mode, data = {}) => {
        if (!modalForm) return;

        // Reset base state
        modalForm.reset();
        formInputs.forEach(input => input.disabled = false);
        if (saveBtn) saveBtn.style.display = 'block';

        if (mode === 'add') {
            if (modalTitle) modalTitle.textContent = 'Add New Supplier';
            if (saveBtn) saveBtn.textContent = 'Save Supplier';
            if (cancelModalBtn) cancelModalBtn.textContent = 'Cancel';
        } else if (mode === 'edit') {
            if (modalTitle) modalTitle.textContent = 'Edit Supplier';
            if (saveBtn) saveBtn.textContent = 'Update Supplier';
            if (cancelModalBtn) cancelModalBtn.textContent = 'Cancel';
            fillForm(data);
        } else if (mode === 'view') {
            if (modalTitle) modalTitle.textContent = 'View Supplier Details';
            // Set form to read-only
            formInputs.forEach(input => input.disabled = true);
            // Hide Save button
            if (saveBtn) saveBtn.style.display = 'none';
            if (cancelModalBtn) cancelModalBtn.textContent = 'Close';
            fillForm(data);
        }
    };

    // --- 1.4 Open/Close Logic ---
    const openModal = () => {
        if (backdrop) {
            backdrop.style.display = 'flex';
            setTimeout(() => {
                backdrop.classList.add('is-open');
            }, 10);
        }
    };

    const closeModal = () => {
        if (backdrop) {
            backdrop.classList.remove('is-open');
            setTimeout(() => {
                backdrop.style.display = 'none';
            }, 350);
        }
        if (modalForm) modalForm.reset();
        setModalMode('add');
    };

    // --- 1.5 Event Listeners for Supplier Modal ---
    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => {
            setModalMode('add');
            openModal();
        });
    }
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
    
    if (backdrop) {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) closeModal();
        });
    }

    // Handle Edit Buttons
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', (e) => {
            const supplierId = e.currentTarget.dataset.supplierId;
            const supplierData = fetchSupplierData(supplierId);
            setModalMode('edit', supplierData);
            openModal();
        });
    });

    // Handle View Buttons
    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', (e) => {
            const supplierId = e.currentTarget.dataset.supplierId;
            const supplierData = fetchSupplierData(supplierId);
            setModalMode('view', supplierData);
            openModal();
        });
    });

    // Handle Form Submit
    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (saveBtn && saveBtn.style.display === 'none') {
                closeModal();
                return;
            }

            const title = document.getElementById('modal-title').textContent;
            console.log(`${title} data submitted (Mock)`);
            
            // Mock Success Alert
            alert(`Success: ${title}`);
            closeModal();
        });
    }


    // ============================================================
    // PART 2: CHECKBOX & BULK ACTIONS (FIXED LOGIC)
    // ============================================================
    
    // 1. เลือกเฉพาะ Checkbox ที่เป็น "รายการลูก" (ไม่เอา Select All)
    const itemCheckboxes = document.querySelectorAll('.inv-checkbox:not(#select-all-checkbox)');
    // 2. เลือกตัว Select All แยกต่างหาก
    const selectAllBox = document.getElementById('select-all-checkbox');
    
    const bulkActionsPanel = document.getElementById('bulk-actions');
    const selectedCountSpan = document.getElementById('selected-count');
    
    // ฟังก์ชันอัปเดตสถานะ Bulk Actions
    const updateBulkActions = () => {
        const selectedBoxes = Array.from(itemCheckboxes).filter(box => box.classList.contains('active'));
        const count = selectedBoxes.length;

        if (selectedCountSpan) selectedCountSpan.textContent = count;

        if (bulkActionsPanel) {
            bulkActionsPanel.style.display = (count > 0) ? 'flex' : 'none';
        }

        // อัปเดต Select All ว่าควรติ๊กหรือไม่
        if (selectAllBox) {
            const allSelected = (count === itemCheckboxes.length) && (count > 0);
            if (allSelected) {
                selectAllBox.classList.add('active');
            } else {
                selectAllBox.classList.remove('active');
            }
        }
    };

    // Event Listeners ให้ "Checkbox ลูก"
    itemCheckboxes.forEach(box => {
        box.addEventListener('click', function() {
            this.classList.toggle('active');
            
            const parentRow = this.closest('.purchasing-list-row');
            if (parentRow) parentRow.classList.toggle('selected-row');

            updateBulkActions();
        });
    });

    // Event Listener ให้ "Select All"
    if (selectAllBox) {
        selectAllBox.addEventListener('click', function() {
            const isChecking = !this.classList.contains('active');
            
            if (isChecking) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }

            itemCheckboxes.forEach(box => {
                const parentRow = box.closest('.purchasing-list-row');
                if (isChecking) {
                    box.classList.add('active');
                    if(parentRow) parentRow.classList.add('selected-row');
                } else {
                    box.classList.remove('active');
                    if(parentRow) parentRow.classList.remove('selected-row');
                }
            });

            const count = isChecking ? itemCheckboxes.length : 0;
            if (selectedCountSpan) selectedCountSpan.textContent = count;
            if (bulkActionsPanel) bulkActionsPanel.style.display = (count > 0) ? 'flex' : 'none';
        });
    }

    // Delete Logic (Mock)
    const bulkDeleteBtn = bulkActionsPanel ? bulkActionsPanel.querySelector('.fa-trash')?.closest('button') : null;
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', () => {
            const selectedIds = Array.from(itemCheckboxes)
                                     .filter(box => box.classList.contains('active'))
                                     .map(box => box.dataset.id);
            
            if (selectedIds.length > 0) {
                if(confirm(`Are you sure you want to delete ${selectedIds.length} items?`)) {
                    console.log('Deleting IDs:', selectedIds);
                    alert('Mock delete success!');
                    
                    // Reset UI
                    if (selectAllBox) selectAllBox.classList.remove('active');
                    itemCheckboxes.forEach(box => {
                        box.classList.remove('active');
                        box.closest('.purchasing-list-row')?.classList.remove('selected-row');
                    });
                    updateBulkActions();
                }
            }
        });
    }


    // ============================================================
    // PART 3: PO PAGE LOGIC (SLIDER, MODAL, SEARCH)
    // ============================================================

    // --- 3.1 Sliding Filter ---
    const slider = document.getElementById('po-status-filter');
    if (slider) {
        const buttons = slider.querySelectorAll('.toggle-btn');
        const poItems = document.querySelectorAll('.po-item');

        const setSliderPosition = (targetButton) => {
            if (!targetButton) return;
            slider.style.setProperty('--slider-left', `${targetButton.offsetLeft}px`);
            slider.style.setProperty('--slider-width', `${targetButton.offsetWidth}px`);
        };

        const activeBtn = slider.querySelector('.toggle-btn.active');
        setSliderPosition(activeBtn);
        setTimeout(() => slider.classList.add('slider-ready'), 50);

        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const clickedBtn = e.currentTarget;
                buttons.forEach(b => b.classList.remove('active'));
                clickedBtn.classList.add('active');
                setSliderPosition(clickedBtn);

                const filterValue = clickedBtn.dataset.filter;
                poItems.forEach(row => {
                    const rowStatus = row.dataset.status;
                    if (filterValue === 'all') {
                        row.style.display = 'grid';
                    } else {
                        row.style.display = (rowStatus === filterValue) ? 'grid' : 'none';
                    }
                });
            });
        });

        window.addEventListener('resize', () => {
            const currentActive = slider.querySelector('.toggle-btn.active');
            setSliderPosition(currentActive);
        });
    }

    // --- 3.2 PO Modal ---
    const poBackdrop = document.getElementById('po-modal-backdrop');
    const openPoBtn = document.getElementById('open-po-modal');
    const closePoBtn = document.getElementById('close-po-modal-btn');
    const cancelPoBtn = document.getElementById('cancel-po-modal-btn');
    const poForm = document.getElementById('create-po-form');

    const openPoModal = () => {
        if (poBackdrop) {
            poBackdrop.style.display = 'flex';
            const dateInput = document.getElementById('po_date');
            if(dateInput && !dateInput.value) dateInput.valueAsDate = new Date();
            setTimeout(() => poBackdrop.classList.add('is-open'), 10);
        }
    };

    const closePoModal = () => {
        if (poBackdrop) {
            poBackdrop.classList.remove('is-open');
            setTimeout(() => {
                poBackdrop.style.display = 'none';
                if(poForm) poForm.reset();
            }, 350);
        }
    };

    if (openPoBtn) openPoBtn.addEventListener('click', openPoModal);
    if (closePoBtn) closePoBtn.addEventListener('click', closePoModal);
    if (cancelPoBtn) cancelPoBtn.addEventListener('click', closePoModal);
    if (poBackdrop) poBackdrop.addEventListener('click', (e) => { if (e.target === poBackdrop) closePoModal(); });

    if (poForm) {
        poForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert("Purchase Order Draft Created! (Mock)");
            closePoModal();
        });
    }

    // --- 3.3 Search Bar ---
    const searchInput = document.getElementById('po-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const poItems = document.querySelectorAll('.po-item');
            poItems.forEach(row => {
                const poNum = row.querySelector('.col-po-number')?.textContent.toLowerCase() || '';
                const supplier = row.querySelector('.col-supplier')?.textContent.toLowerCase() || '';
                if (poNum.includes(searchTerm) || supplier.includes(searchTerm)) {
                     row.style.display = 'grid';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }


    // ============================================================
    // PART 4: GOODS RECEIVED (GR) LOGIC
    // ============================================================

    // --- 4.1 GR Modal ---
    const grBackdrop = document.getElementById('gr-modal-backdrop');
    const openGrBtn = document.getElementById('open-gr-modal');
    const closeGrBtn = document.getElementById('close-gr-modal-btn');
    const cancelGrBtn = document.getElementById('cancel-gr-modal-btn');
    const grForm = document.getElementById('create-gr-form');

    const openGrModal = () => {
        if (grBackdrop) {
            grBackdrop.style.display = 'flex';
            const grDate = document.getElementById('gr_date');
            if(grDate && !grDate.value) grDate.valueAsDate = new Date();
            setTimeout(() => grBackdrop.classList.add('is-open'), 10);
        }
    };

    const closeGrModal = () => {
        if (grBackdrop) {
            grBackdrop.classList.remove('is-open');
            setTimeout(() => {
                grBackdrop.style.display = 'none';
                if(grForm) grForm.reset();
            }, 350);
        }
    };

    if (openGrBtn) openGrBtn.addEventListener('click', openGrModal);
    if (closeGrBtn) closeGrBtn.addEventListener('click', closeGrModal);
    if (cancelGrBtn) cancelGrBtn.addEventListener('click', closeGrModal);
    if (grBackdrop) grBackdrop.addEventListener('click', (e) => { if (e.target === grBackdrop) closeGrModal(); });

    if (grForm) {
        grForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(grForm);
            const selectedPO = formData.get('po_id');
            closeGrModal();
            
            // Switch view to Receive View
            const poReceiveView = document.getElementById('po-receive-view');
            const poSearchView = document.getElementById('po-search-view');
            if (poReceiveView && poSearchView) {
                poSearchView.style.display = 'none';
                poReceiveView.style.display = 'block';
                const title = poReceiveView.querySelector('h2');
                if(title) title.textContent = `Receiving Items for ${selectedPO}`;
            }
        });
    }

    // --- 4.2 View Toggles ---
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