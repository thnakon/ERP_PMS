<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders / Sales - ประวัติการขาย</title>
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <link rel="stylesheet" href="resources/css/orders-sales.css">
    </head>

    <body>


        <div class="os-container">
            {{-- [!!! REFACTORED HEADER !!!] --}}
            <div class="sr-header">
                <div class="sr-header-left">
                    <p class="sr-breadcrumb">
                        Dashboard / <span style="color: #3a3a3c; font-weight: 600;">Orders-Sales</span>
                    </p>

                    <h2 class="sr-page-title">Orders | Sales (6)</h2>
                </div>

                <div class="sr-header-right" style="margin-right: 10px">
                    <button class="sr-icon-button" title="Filter">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
                และใช้คลาสใหม่ sr-button-primary --}}
                    <a href="{{ route('pos.index') }}"><button class="sr-button-primary">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add new Orders</span>
                        </button></a>
                </div>
            </div>

            <!-- 1. ส่วนตัวกรอง (Filters) และการค้นหา -->
            <div class="os-filters-wrapper">
                <!-- Search Bar -->
                <div class="os-filter-group search">
                    <label for="search-input"><i class="fa-solid fa-search os-icon"></i> ค้นหาบิล / ลูกค้า</label>
                    <input type="text" id="search-input" class="os-input"
                        placeholder="เลขที่บิล, เบอร์โทร, หรือชื่อลูกค้า">
                </div>

                <!-- Filter by Date -->
                <div class="os-filter-group">
                    <label for="date-filter"><i class="fa-solid fa-calendar-days os-icon"></i> กรองตามวันที่</label>
                    <select id="date-filter" class="os-select">
                        <option value="today">วันนี้</option>
                        <option value="yesterday">เมื่อวาน</option>
                        <option value="last7days">7 วันที่ผ่านมา</option>
                        <option value="thismonth">เดือนนี้</option>
                        <option value="custom">กำหนดเอง...</option>
                    </select>
                </div>

                <!-- Filter by Staff -->
                <div class="os-filter-group">
                    <label for="staff-filter"><i class="fa-solid fa-user-doctor os-icon"></i> กรองตามพนักงาน</label>
                    <select id="staff-filter" class="os-select">
                        <option value="">-- พนักงานทั้งหมด --</option>
                        <option value="somsak">สมศักดิ์ เภสัชกร</option>
                        <option value="mala">มาลา พนักงานขาย</option>
                    </select>
                </div>

                <!-- Filter by Status -->
                <div class="os-filter-group">
                    <label for="status-filter"><i class="fa-solid fa-circle-check os-icon"></i> กรองตามสถานะ</label>
                    <select id="status-filter" class="os-select">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="Completed">Completed (สำเร็จ)</option>
                        <option value="Refunded">Refunded (คืนเงินแล้ว)</option>
                        <option value="Cancelled">Cancelled (ยกเลิก)</option>
                    </select>
                </div>

                <!-- Refresh Button -->
                <button id="refresh-btn" class="os-icon-button" title="โหลดข้อมูลใหม่"><i
                        class="fa-solid fa-arrows-rotate"></i></button>

            </div>

            <!-- 2. ตารางสรุปรายการบิล (Orders List Table) -->
            <div class="os-list-container">
                <!-- Header Row -->
                <div class="os-list-row header-row">
                    <div class="os-col os-col-receipt-no">เลขที่บิล</div>
                    <div class="os-col os-col-datetime">วันที่/เวลา</div>
                    <div class="os-col os-col-customer">ลูกค้า</div>
                    <div class="os-col os-col-staff">พนักงาน</div>
                    <div class="os-col os-col-total" style="text-align: right;">ยอดรวม (฿)</div>
                    <div class="os-col os-col-status">สถานะ</div>
                    <div class="os-col os-col-actions" style="justify-self: flex-end;">จัดการ</div>
                </div>

                <!-- Data Rows (Rendered by JS - Mock Data for display) -->
                <div id="orders-list-body">
                    <!-- Mock Order 1: Completed -->
                    <div class="os-list-row order-row" data-order-id="1001" onclick="viewOrderDetails('1001')">
                        <div class="os-col os-col-receipt-no" data-label="เลขที่บิล">ORD-2025-00123</div>
                        <div class="os-col os-col-datetime" data-label="วันที่/เวลา">2025-11-18 10:30</div>
                        <div class="os-col os-col-customer" data-label="ลูกค้า">สมศรี ใจดี</div>
                        <div class="os-col os-col-staff" data-label="พนักงาน">สมศักดิ์</div>
                        <div class="os-col os-col-total total-col" data-label="ยอดรวม (฿)">350.00</div>
                        <div class="os-col os-col-status" data-label="สถานะ"><span
                                class="os-status-badge completed">Completed</span></div>
                        <div class="os-col os-col-actions actions-col" data-label="จัดการ">
                            <button class="os-view-btn" onclick="event.stopPropagation(); viewOrderDetails('1001');"><i
                                    class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>

                    <!-- Mock Order 2: Refunded -->
                    <div class="os-list-row order-row" data-order-id="1002" onclick="viewOrderDetails('1002')">
                        <div class="os-col os-col-receipt-no" data-label="เลขที่บิล">ORD-2025-00122</div>
                        <div class="os-col os-col-datetime" data-label="วันที่/เวลา">2025-11-17 14:45</div>
                        <div class="os-col os-col-customer" data-label="ลูกค้า">Walk-in Customer</div>
                        <div class="os-col os-col-staff" data-label="พนักงาน">มาลา</div>
                        <div class="os-col os-col-total total-col" data-label="ยอดรวม (฿)">120.00</div>
                        <div class="os-col os-col-status" data-label="สถานะ"><span
                                class="os-status-badge refunded">Refunded</span></div>
                        <div class="os-col os-col-actions actions-col" data-label="จัดการ">
                            <button class="os-view-btn"
                                onclick="event.stopPropagation(); viewOrderDetails('1002');"><i
                                    class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>

                    <!-- Mock Order 3: Cancelled (Void) -->
                    <div class="os-list-row order-row" data-order-id="1003" onclick="viewOrderDetails('1003')">
                        <div class="os-col os-col-receipt-no" data-label="เลขที่บิล">ORD-2025-00121</div>
                        <div class="os-col os-col-datetime" data-label="วันที่/เวลา">2025-11-17 09:15</div>
                        <div class="os-col os-col-customer" data-label="ลูกค้า">พงษ์ศักดิ์ รุ่งเรือง</div>
                        <div class="os-col os-col-staff" data-label="พนักงาน">สมศักดิ์</div>
                        <div class="os-col os-col-total total-col" data-label="ยอดรวม (฿)">890.50</div>
                        <div class="os-col os-col-status" data-label="สถานะ"><span
                                class="os-status-badge cancelled">Cancelled</span></div>
                        <div class="os-col os-col-actions actions-col" data-label="จัดการ">
                            <button class="os-view-btn"
                                onclick="event.stopPropagation(); viewOrderDetails('1003');"><i
                                    class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>

                    <!-- Row for No Results (Hidden by default) -->
                    <div id="no-results-row" class="os-list-row"
                        style="display: none; justify-content: center; color: var(--text-secondary);">
                        ไม่พบรายการบิลตามเงื่อนไขที่กำหนด
                    </div>
                </div>
            </div>

            {{-- [!!! PAGINATION !!!] --}}
            <div class="people-pagination">
                <span class="pagination-text">1-8 of 28</span>
                <div class="pagination-controls">
                    <button class="pagination-btn disabled" aria-label="Previous Page">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="pagination-btn" aria-label="Next Page">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- 3. หน้าต่าง/Modal "ดูรายละเอียดบิล" (View Details) -->
        <div id="order-details-modal" class="os-modal-overlay">
            <div class="os-modal-content">
                <!-- Header -->
                <div class="os-modal-header">
                    <div>
                        <h2 id="modal-receipt-no"><i class="fa-solid fa-file-invoice os-icon"></i> รายละเอียดบิล
                            #ORD-2025-00123</h2>
                        <div class="os-detail-grid">
                            <div class="os-detail-item">
                                <span class="os-detail-label">วันที่/เวลา:</span>
                                <span id="modal-date-time" class="os-detail-value">2025-11-18 10:30</span>
                            </div>
                            <div class="os-detail-item">
                                <span class="os-detail-label">สถานะ:</span>
                                <span id="modal-status"
                                    class="os-detail-value os-status-badge completed">Completed</span>
                            </div>
                            <div class="os-detail-item">
                                <span class="os-detail-label">ลูกค้า:</span>
                                <span id="modal-customer" class="os-detail-value">สมศรี ใจดี (081-XXX-XXXX)</span>
                            </div>
                            <div class="os-detail-item">
                                <span class="os-detail-label">พนักงาน:</span>
                                <span id="modal-staff" class="os-detail-value">สมศักดิ์ (เภสัชกร)</span>
                            </div>
                        </div>
                    </div>
                    <button class="os-modal-close" id="modal-close-btn">&times;</button>
                </div>

                <!-- รายการยา/สินค้า (Item List) -->
                <h3 style="font-size: 1.2rem; margin-top: 0; color: var(--text-primary);">รายการสินค้าในบิล</h3>
                <table class="os-item-list-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคา/หน่วย (฿)</th>
                            <th style="text-align: right;">รวม (฿)</th>
                        </tr>
                    </thead>
                    <tbody id="modal-item-list-body">
                        <!-- Items will be populated by JavaScript -->
                    </tbody>
                </table>

                <div style="display: flex; justify-content: flex-end;">
                    <!-- สรุปยอด (Financials) และ วิธีชำระเงิน -->
                    <div style="display: flex; gap: 30px;">
                        <div style="min-width: 150px;">
                            <h3
                                style="font-size: 1rem; margin-top: 0; margin-bottom: 10px; color: var(--text-secondary);">
                                วิธีชำระเงิน</h3>
                            <p id="modal-payment-method" class="os-detail-value" style="font-size: 1rem;">เงินสด</p>
                        </div>

                        <div class="os-financial-summary">
                            <div class="os-summary-row">
                                <span class="os-summary-label">ยอดรวมย่อย (Subtotal)</span>
                                <span id="modal-subtotal" class="os-summary-value">345.79</span>
                            </div>
                            <div class="os-summary-row">
                                <span class="os-summary-label">ส่วนลด (Discount)</span>
                                <span id="modal-discount" class="os-summary-value">-0.00</span>
                            </div>
                            <div class="os-summary-row">
                                <span class="os-summary-label">ภาษีมูลค่าเพิ่ม (VAT 7%)</span>
                                <span id="modal-vat" class="os-summary-value">24.21</span>
                            </div>
                            <div class="os-summary-row grand-total">
                                <span class="os-summary-label">ยอดสุทธิ (Grand Total)</span>
                                <span id="modal-grand-total" class="os-summary-value">370.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ส่วนปุ่ม (Actions) -->
                <div class="os-modal-actions">
                    <!-- Print Receipt -->
                    <button id="modal-print-btn" class="os-button-primary os-action-btn-print">
                        <i class="fa-solid fa-print"></i> พิมพ์ใบเสร็จซ้ำ
                    </button>
                    <!-- Refund / Return (ใช้ Primary Color) -->
                    <button id="modal-refund-btn" class="os-button-primary os-action-btn-refund">
                        <i class="fa-solid fa-rotate-left"></i> ทำเรื่องคืนเงิน / คืนของ
                    </button>
                    <!-- Void / Cancel (ใช้ Red Color) -->
                    <button id="modal-void-btn" class="os-button-primary os-action-btn-void">
                        <i class="fa-solid fa-ban"></i> ยกเลิกบิล (Void)
                    </button>
                </div>
            </div>
        </div>

        <script src="resources/js/orders-sales.js"></script>
    </body>

    </html>
</x-app-layout>


