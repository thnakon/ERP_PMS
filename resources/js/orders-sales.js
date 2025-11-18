// Mock data for demonstration purposes
const allOrders = [
    {
        id: '1001',
        receiptNo: 'ORD-2025-00123',
        dateTime: '2025-11-18 10:30',
        customer: 'สมศรี ใจดี',
        customerPhone: '081-555-1234',
        staff: 'สมศักดิ์',
        staffRole: 'เภสัชกร',
        status: 'Completed',
        subtotal: 345.79,
        discount: 0.00,
        vat: 24.21,
        total: 370.00,
        paymentMethod: 'โอน QR PromptPay',
        items: [
            { name: 'ยาพาราเซตามอล 500mg (10 เม็ด)', qty: 2, pricePerUnit: 15.00, total: 30.00 },
            { name: 'หน้ากากอนามัย 3D (ซอง)', qty: 5, pricePerUnit: 12.00, total: 60.00 },
            { name: 'Vitamin C 1000mg', qty: 1, pricePerUnit: 280.00, total: 280.00 },
        ]
    },
    {
        id: '1002',
        receiptNo: 'ORD-2025-00122',
        dateTime: '2025-11-17 14:45',
        customer: 'Walk-in Customer',
        customerPhone: 'N/A',
        staff: 'มาลา',
        staffRole: 'พนักงานขาย',
        status: 'Refunded',
        subtotal: 112.15,
        discount: 0.00,
        vat: 7.85,
        total: 120.00,
        paymentMethod: 'เงินสด',
        items: [
            { name: 'น้ำเกลือแร่ (ขวด)', qty: 1, pricePerUnit: 45.00, total: 45.00 },
            { name: 'แอลกอฮอล์ล้างมือ 70% (250ml)', qty: 2, pricePerUnit: 37.50, total: 75.00 },
        ]
    },
    {
        id: '1003',
        receiptNo: 'ORD-2025-00121',
        dateTime: '2025-11-17 09:15',
        customer: 'พงษ์ศักดิ์ รุ่งเรือง',
        customerPhone: '089-999-5678',
        staff: 'สมศักดิ์',
        staffRole: 'เภสัชกร',
        status: 'Cancelled',
        subtotal: 832.24,
        discount: 50.00,
        vat: 58.26,
        total: 840.50,
        paymentMethod: 'บัตรเครดิต',
        items: [
            { name: 'ยาลดไข้สำหรับเด็ก (ขวด)', qty: 1, pricePerUnit: 150.00, total: 150.00 },
            { name: 'ชุดตรวจโควิด ATK', qty: 3, pricePerUnit: 230.17, total: 690.50 },
        ]
    },
    // เพิ่ม Mock Data อื่นๆ เพื่อให้ตารางดูเต็มขึ้น
    { id: '1004', receiptNo: 'ORD-2025-00120', dateTime: '2025-11-16 19:00', customer: 'Walk-in Customer', customerPhone: 'N/A', staff: 'มาลา', staffRole: 'พนักงานขาย', status: 'Completed', subtotal: 93.46, discount: 0.00, vat: 6.54, total: 100.00, paymentMethod: 'เงินสด', items: [{ name: 'ยาอมแก้ไอ', qty: 3, pricePerUnit: 31.15, total: 93.46 }] },
    { id: '1005', receiptNo: 'ORD-2025-00119', dateTime: '2025-11-16 11:20', customer: 'วิไลวรรณ ดีเลิศ', customerPhone: '090-000-1111', staff: 'สมศักดิ์', staffRole: 'เภสัชกร', status: 'Completed', subtotal: 1401.87, discount: 100.00, vat: 98.13, total: 1400.00, paymentMethod: 'โอน QR PromptPay', items: [] },
    { id: '1006', receiptNo: 'ORD-2025-00118', dateTime: '2025-11-15 08:05', customer: 'Walk-in Customer', customerPhone: 'N/A', staff: 'มาลา', staffRole: 'พนักงานขาย', status: 'Completed', subtotal: 46.73, discount: 0.00, vat: 3.27, total: 50.00, paymentMethod: 'เงินสด', items: [] },
];

const elements = {
    // Filters
    searchInput: document.getElementById('search-input'),
    dateFilter: document.getElementById('date-filter'),
    staffFilter: document.getElementById('staff-filter'),
    statusFilter: document.getElementById('status-filter'),
    refreshBtn: document.getElementById('refresh-btn'),
    
    // Table
    ordersListBody: document.getElementById('orders-list-body'),
    noResultsRow: document.getElementById('no-results-row'),

    // Modal
    modalOverlay: document.getElementById('order-details-modal'),
    modalCloseBtn: document.getElementById('modal-close-btn'),
    modalReceiptNo: document.getElementById('modal-receipt-no'),
    modalDateTime: document.getElementById('modal-date-time'),
    modalStatus: document.getElementById('modal-status'),
    modalCustomer: document.getElementById('modal-customer'),
    modalStaff: document.getElementById('modal-staff'),
    modalItemListBody: document.getElementById('modal-item-list-body'),
    modalSubtotal: document.getElementById('modal-subtotal'),
    modalDiscount: document.getElementById('modal-discount'),
    modalVat: document.getElementById('modal-vat'),
    modalGrandTotal: document.getElementById('modal-grand-total'),
    modalPaymentMethod: document.getElementById('modal-payment-method'),
    modalPrintBtn: document.getElementById('modal-print-btn'),
    modalRefundBtn: document.getElementById('modal-refund-btn'),
    modalVoidBtn: document.getElementById('modal-void-btn'),
};

/**
 * ฟังก์ชันสำหรับฟอร์แมตตัวเลขเป็นสกุลเงิน (บาท)
 * @param {number} amount 
 * @returns {string}
 */
const formatCurrency = (amount) => {
    return amount.toFixed(2);
};

/**
 * สร้าง HTML สำหรับสถานะ (Badge)
 * @param {string} status 
 * @returns {string}
 */
const getStatusBadgeHtml = (status) => {
    const statusClass = status.toLowerCase(); // completed, refunded, cancelled
    return `<span class="os-status-badge ${statusClass}">${status}</span>`;
};

/**
 * แปลงข้อมูล Order เป็น HTML Row สำหรับตาราง
 * @param {object} order 
 * @returns {string}
 */
const createOrderRow = (order) => {
    return `
        <div class="os-list-row order-row" data-order-id="${order.id}" onclick="viewOrderDetails('${order.id}')">
            <div class="os-col os-col-receipt-no" data-label="เลขที่บิล">${order.receiptNo}</div>
            <div class="os-col os-col-datetime" data-label="วันที่/เวลา">${order.dateTime}</div>
            <div class="os-col os-col-customer" data-label="ลูกค้า">${order.customer}</div>
            <div class="os-col os-col-staff" data-label="พนักงาน">${order.staff}</div>
            <div class="os-col os-col-total total-col" data-label="ยอดรวม (฿)">${formatCurrency(order.total)}</div>
            <div class="os-col os-col-status" data-label="สถานะ">${getStatusBadgeHtml(order.status)}</div>
            <div class="os-col os-col-actions actions-col" data-label="จัดการ">
                <button class="os-view-btn" title="ดูรายละเอียด" onclick="event.stopPropagation(); viewOrderDetails('${order.id}');">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>
    `;
};

/**
 * เรนเดอร์ตารางรายการบิลตามข้อมูลที่กรองแล้ว
 * @param {Array<object>} orders
 */
const renderOrdersTable = (orders) => {
    if (orders.length === 0) {
        elements.ordersListBody.innerHTML = '';
        elements.noResultsRow.style.display = 'flex';
        return;
    }
    
    elements.noResultsRow.style.display = 'none';
    const rowsHtml = orders.map(createOrderRow).join('');
    elements.ordersListBody.innerHTML = rowsHtml;
};

/**
 * ฟังก์ชันหลักในการกรองและค้นหาข้อมูล
 */
const filterAndSearchOrders = () => {
    const searchText = elements.searchInput.value.toLowerCase();
    const staff = elements.staffFilter.value;
    const status = elements.statusFilter.value;
    const dateRange = elements.dateFilter.value; // สำหรับการสาธิตนี้จะใช้แค่ค่า

    let filteredOrders = allOrders.filter(order => {
        // 1. Search Bar (Receipt No. / Customer)
        const matchesSearch = searchText === '' ||
            order.receiptNo.toLowerCase().includes(searchText) ||
            order.customer.toLowerCase().includes(searchText) ||
            order.customerPhone.includes(searchText);

        // 2. Filter by Staff
        const matchesStaff = staff === '' || order.staff.toLowerCase() === staff.toLowerCase();

        // 3. Filter by Status
        const matchesStatus = status === '' || order.status === status;

        // 4. Filter by Date (Simple Mock)
        // ในระบบจริงต้องมีการคำนวณช่วงวันที่จริง
        let matchesDate = true;
        if (dateRange === 'today') {
             // Mock logic: Check if date is 2025-11-18 (based on mock data)
            matchesDate = order.dateTime.startsWith('2025-11-18');
        } else if (dateRange === 'yesterday') {
             // Mock logic: Check if date is 2025-11-17
            matchesDate = order.dateTime.startsWith('2025-11-17');
        }
        // for other range filters, they would have real date logic here.

        return matchesSearch && matchesStaff && matchesStatus && matchesDate;
    });

    renderOrdersTable(filteredOrders);
};

/**
 * แสดง Modal รายละเอียดบิล
 * @param {string} orderId
 */
window.viewOrderDetails = (orderId) => {
    const order = allOrders.find(o => o.id === orderId);

    if (!order) {
        console.error('ไม่พบข้อมูลบิลสำหรับ ID:', orderId);
        return;
    }

    // 1. อัพเดท Header
    elements.modalReceiptNo.innerHTML = `<i class="fa-solid fa-file-invoice os-icon"></i> รายละเอียดบิล #${order.receiptNo}`;
    elements.modalDateTime.textContent = order.dateTime;
    elements.modalCustomer.textContent = `${order.customer} ${order.customerPhone !== 'N/A' ? `(${order.customerPhone})` : ''}`;
    elements.modalStaff.textContent = `${order.staff} (${order.staffRole})`;
    
    // อัพเดท Status Badge
    elements.modalStatus.className = `os-detail-value os-status-badge ${order.status.toLowerCase()}`;
    elements.modalStatus.textContent = order.status;
    
    // 2. อัพเดท Item List
    const itemsHtml = order.items.map(item => `
        <tr>
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>${formatCurrency(item.pricePerUnit)}</td>
            <td style="text-align: right; font-weight: 600;">${formatCurrency(item.total)}</td>
        </tr>
    `).join('');
    elements.modalItemListBody.innerHTML = itemsHtml.length > 0 ? itemsHtml : '<tr><td colspan="4" style="text-align: center; color: var(--text-secondary);">ไม่มีรายการสินค้า</td></tr>';

    // 3. อัพเดท Financial Summary
    elements.modalSubtotal.textContent = formatCurrency(order.subtotal);
    elements.modalDiscount.textContent = `-${formatCurrency(order.discount)}`;
    elements.modalVat.textContent = formatCurrency(order.vat);
    elements.modalGrandTotal.textContent = formatCurrency(order.total);
    elements.modalPaymentMethod.textContent = order.paymentMethod;

    // 4. ตั้งค่าปุ่ม Actions
    // ลิงก์ฟังก์ชันที่จะถูกเรียกเมื่อกดปุ่ม (ในระบบจริง)
    elements.modalPrintBtn.onclick = () => console.log(`Printing receipt: ${order.receiptNo}`);
    elements.modalRefundBtn.onclick = () => console.log(`Initiating refund for: ${order.receiptNo}`);
    elements.modalVoidBtn.onclick = () => console.log(`Voiding order: ${order.receiptNo}`);

    // ปิด/เปิดการใช้งานปุ่มตามสถานะ (Audit Trail)
    if (order.status === 'Refunded' || order.status === 'Cancelled') {
        elements.modalRefundBtn.disabled = true;
        elements.modalRefundBtn.style.opacity = 0.5;
        elements.modalVoidBtn.disabled = true;
        elements.modalVoidBtn.style.opacity = 0.5;
    } else {
        elements.modalRefundBtn.disabled = false;
        elements.modalRefundBtn.style.opacity = 1;
        elements.modalVoidBtn.disabled = false;
        elements.modalVoidBtn.style.opacity = 1;
    }


    // 5. แสดง Modal
    elements.modalOverlay.classList.add('active');
};

/**
 * ซ่อน Modal รายละเอียดบิล
 */
const hideOrderDetailsModal = () => {
    elements.modalOverlay.classList.remove('active');
};

// --- Event Listeners ---

// 1. Initial Load
document.addEventListener('DOMContentLoaded', () => {
    renderOrdersTable(allOrders);
});

// 2. Filter Changes
[elements.searchInput, elements.dateFilter, elements.staffFilter, elements.statusFilter].forEach(el => {
    if (el) {
        el.addEventListener('input', filterAndSearchOrders);
        el.addEventListener('change', filterAndSearchOrders);
    }
});

// 3. Refresh Button
elements.refreshBtn.addEventListener('click', () => {
    // Clear filters and re-render
    elements.searchInput.value = '';
    elements.dateFilter.value = 'today';
    elements.staffFilter.value = '';
    elements.statusFilter.value = '';
    filterAndSearchOrders();
    console.log('ข้อมูลรายการบิลถูกโหลดใหม่');
});

// 4. Modal Close
if (elements.modalCloseBtn) {
    elements.modalCloseBtn.addEventListener('click', hideOrderDetailsModal);
}

// 5. Close modal by clicking outside
if (elements.modalOverlay) {
    elements.modalOverlay.addEventListener('click', (e) => {
        // Only close if the click target is the overlay itself, not the content
        if (e.target === elements.modalOverlay) {
            hideOrderDetailsModal();
        }
    });
}