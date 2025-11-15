{{-- 
  สมมติว่าไฟล์นี้ถูกเรียกใช้ภายใน Layout หลัก (เช่น app.blade.php)
  ที่ใช้ <x-app-layout> ซึ่งมี <main> และ {{ $slot }} อยู่แล้ว
  เราจะใช้ <x-app-layout> เพื่อรวมทุกอย่างเข้าด้วยกัน
--}}

<x-app-layout>
    {{-- 
      [!!! HEADER SLOT !!!] 
      นี่คือส่วนหัวของหน้า (Header) ที่คุณให้มา
      เราจะปรับแต่งเล็กน้อยตามที่คุณขอ
    --}}

    {{-- 
      [!!! MAIN CONTENT !!!] 
      นี่คือเนื้อหาหลักของหน้ารายงานการขาย
      ที่จะแสดงภายใน <main class="main-content-wrapper"> ของคุณ
    --}}

    {{-- ลิงก์ไปยัง CSS และ JS เฉพาะของหน้านี้ --}}
    <link rel="stylesheet" href="{{ asset('resources/css/sale-report.css') }}">

    {{-- CDN สำหรับ Chart.js (เพื่อวาดกราฟ) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    {{-- [!!! REFACTORED !!!] อัปเดตชื่อคลาสทั้งหมด --}}
    <div class="sr-container">

        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / Settings / Sales-Report</p>
                <h2 class="sr-page-title">Sale Report</h2>
            </div>
            <div class="sr-header-right">
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>เพิ่มรายงานใหม่</span>
                </button>
            </div>
        </div>

        <!-- 1. ตัวกรองข้อมูล (Filters) -->
        <div class="sr-filters-wrapper">
            <div class="sr-filter-group">
                <label for="date-range-filter">
                    <i class="fa-solid fa-calendar-days"></i> ช่วงเวลา
                </label>
                <select id="date-range-filter" class="sr-select">
                    <option value="today">วันนี้</option>
                    <option value="yesterday">เมื่อวาน</option>
                    <option value="this_week">สัปดาห์นี้</option>
                    <option value="this_month" selected>เดือนนี้</option>
                    <option value="custom">เลือกช่วงเอง</option>
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="staff-filter">
                    <i class="fa-solid fa-user-doctor"></i> พนักงาน
                </label>
                <select id="staff-filter" class="sr-select">
                    <option value="all">พนักงานทั้งหมด</option>
                    @isset($staffList)
                        @foreach ($staffList as $staff)
                            <option value="{{ $staff['id'] }}">{{ $staff['name'] }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="category-filter">
                    <i class="fa-solid fa-tags"></i> หมวดหมู่
                </label>
                <select id="category-filter" class="sr-select">
                    <option value="all">หมวดหมู่ทั้งหมด</option>
                    @isset($categoriesList)
                        @foreach ($categoriesList as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="payment-filter">
                    <i class="fa-solid fa-credit-card"></i> การชำระเงิน
                </label>
                <select id="payment-filter" class="sr-select">
                    <option value="all">ทุกการชำระเงิน</option>
                    <option value="cash">เงินสด</option>
                    <option value="transfer">โอน</option>
                    <option value="credit_card">บัตรเครดิต</option>
                </select>
            </div>

            <button class="sr-button-secondary sr-filter-apply-btn">
                <i class="fa-solid fa-filter"></i>
                <span>ใช้ตัวกรอง</span>
            </button>
        </div>

        <!-- 2. ตัวเลขสรุป (Key Metrics / KPIs) -->
        <div class="sr-kpi-grid">
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #E6F7FF; --icon-color: #00A3FF;">
                    <i class="fa-solid fa-baht-sign"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">ยอดขายรวม (Total Revenue)</span>
                    <span class="sr-kpi-value">฿150,000.00</span>
                    <span class="sr-kpi-delta sr-delta-positive">+5.2% เทียบกับช่วงก่อน</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #EBFDEF; --icon-color: #34C759;">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">จำนวนบิล (Total Orders)</span>
                    <span class="sr-kpi-value">520 บิล</span>
                    <span class="sr-kpi-delta sr-delta-positive">+12 บิล</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #FFF8E6; --icon-color: #FF9F0A;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">ยอดเฉลี่ยต่อบิล (AOV)</span>
                    <span class="sr-kpi-value">฿288.46</span>
                    <span class="sr-kpi-delta sr-delta-negative">-฿10.15</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #F0EFFF; --icon-color: #5E5CE6;">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">จำนวนชิ้นที่ขายได้ (Items Sold)</span>
                    <span class="sr-kpi-value">1,200 ชิ้น</span>
                    <span class="sr-kpi-delta sr-delta-neutral">คงที่</span>
                </div>
            </div>
        </div>

        <!-- 3. กราฟ และ ตาราง (Graphs & Tables) -->
        <div class="sr-widgets-grid">

            <!-- Sales Over Time (กราฟเส้น) -->
            <div class="sr-widget-card sr-widget-full-width">
                <h3 class="sr-widget-title">ยอดขายตามช่วงเวลา (Sales Over Time)</h3>
                <div class="sr-chart-container" style="height: 350px;">
                    <canvas id="salesOverTimeChart"></canvas>
                </div>
            </div>

            <!-- Top Categories (กราฟวงกลม) -->
            <div class="sr-widget-card">
                <h3 class="sr-widget-title">ยอดขายตามหมวดหมู่ (Top Categories)</h3>
                <div class="sr-chart-container" style="height: 300px;">
                    <canvas id="topCategoriesChart"></canvas>
                </div>
            </div>

            <!-- Top Staff (ตาราง) -->
            <div class="sr-widget-card">
                <h3 class="sr-widget-title">ยอดขายตามพนักงาน (Top Staff)</h3>
                <div class="sr-table-container">
                    <table class="sr-table">
                        <thead>
                            <tr>
                                <th>ชื่อพนักงาน</th>
                                <th>จำนวนบิล</th>
                                <th>ยอดขายรวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>เภสัชกร ก.</td>
                                <td>180</td>
                                <td>฿65,000.00</td>
                            </tr>
                            <tr>
                                <td>เภสัชกร ข.</td>
                                <td>175</td>
                                <td>฿55,000.00</td>
                            </tr>
                            <tr>
                                <td>พนักงาน ค.</td>
                                <td>165</td>
                                <td>฿30,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Best-Selling Products (ตาราง) -->
            <div class="sr-widget-card sr-widget-full-width">
                <h3 class="sr-widget-title">10 อันดับสินค้าขายดี (Best-Selling Products)</h3>
                <div class="sr-table-container">
                    <table class="sr-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อสินค้า</th>
                                <th>หมวดหมู่</th>
                                <th>จำนวน (ชิ้น)</th>
                                <th>ยอดขายรวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Product A (10mg)</td>
                                <td>ยาอันตราย</td>
                                <td>150</td>
                                <td>฿15,000.00</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Serum B</td>
                                <td>เวชสำอาง</td>
                                <td>80</td>
                                <td>฿12,000.00</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>เครื่องวัดความดัน C</td>
                                <td>อุปกรณ์การแพทย์</td>
                                <td>30</td>
                                <td>฿10,500.00</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Vitamin D (1000 IU)</td>
                                <td>อาหารเสริม</td>
                                <td>200</td>
                                <td>฿9,000.00</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Product E (50mg)</td>
                                <td>ยาอันตราย</td>
                                <td>90</td>
                                <td>฿8,100.00</td>
                            </tr>
                            <!-- ... เพิ่มเติม ... -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- .sr-widgets-grid -->
    </div> <!-- .sr-container -->

    {{-- โหลดไฟล์ JS เฉพาะของหน้านี้ --}}
    <script src="{{ asset('resources/js/sale-report.js') }}"></script>

</x-app-layout>
