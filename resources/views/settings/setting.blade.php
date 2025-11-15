{{-- ใช้ Layout หลักของคุณ (app.blade.php) --}}
<x-app-layout>

    {{-- [!!! ADJUSTED !!!] --}}
    {{-- เราจะไม่ใช้ x-slot "header" แบบเดิม แต่จะสร้าง header ของเราเองข้างล่าง --}}

    {{-- เนื้อหาหลักของหน้า Settings --}}
    <div class="settings-layout">

        <!-- [!!! NEW HEADER !!!] ส่วนหัวของหน้าดีไซน์ใหม่ -->
        <div class="settings-page-header">
            <div class="header-left">
                <p class="breadcrumb">Dashboard / Settings</p>
                <h2 class="settings-page-title">Settings</h2>
            </div>
            <div class="header-right">
                <div class="date-picker-box">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{-- [!!! ADJUSTED !!!] เปลี่ยนจากข้อความ Static เป็น PHP Blade --}}
                    <span>{{ now()->startOfMonth()->format('M d, Y') . ' - ' . now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="settings-content-container">

            <div class="settings-tabs-wrapper">
                <!-- 1. เมนูแท็บ (แนวนอน) -->
                <nav class="settings-tabs-nav">
                    <span class="active-pill-background"></span>
                    <a href="#" class="settings-tab-item active" data-tab="tab-general">
                        <i class="fa-solid fa-building"></i>
                        <span>ทั่วไป | องค์กร</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-users">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>ผู้ใช้งานและสิทธิ์</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-pos">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>การขาย (POS)</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-inventory">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>สต็อกและผลิตภัณฑ์</span>
                </a>
                <a href="#" class="settings-tab-item" data-tab="tab-pharmacy">
                    <i class="fa-solid fa-pills"></i>
                    <span>ร้านยาและคนไข้</span>
                </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-system">
                        <i class="fa-solid fa-bell"></i>
                        <span>ระบบและการแจ้งเตือน</span>
                    </a>
                </nav>
            </div>        
                <!-- 2. ส่วนเนื้อหา (Pane) -->
                <div class="settings-content-pane">

                    <!-- ======================= -->
                    <!--   แท็บ 1: ทั่วไป / องค์กร   -->
                    <!-- ======================= -->
                    <section id="tab-general" class="settings-pane active">
                        
                        <div class="settings-card">
                            <h3 class="card-title">ข้อมูลร้าน (Store Details)</h3>
                            <p class="card-description">ข้อมูลนี้จะปรากฏในใบเสร็จและเอกสารต่างๆ ของคุณ</p>

                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="store_name" class="form-label">ชื่อร้าน</label>
                                    <input type="text" id="store_name" class="form-input" value="Oboun ERP">
                                </div>
                                <div class="form-group">
                                    <label for="store_phone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" id="store_phone" class="form-input"
                                        placeholder="เช่น 081-234-5678">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="store_address" class="form-label">ที่อยู่</label>
                                <textarea id="store_address" class="form-textarea" rows="3"
                                    placeholder="เลขที่, ถนน, ตำบล, อำเภอ, จังหวัด, รหัสไปรษณีย์"></textarea>
                            </div>
                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="tax_id" class="form-label">เลขประจำตัวผู้เสียภาษี (Tax ID)</label>
                                    <input type="text" id="tax_id" class="form-input"
                                        placeholder="กรอกเลข 13 หลัก">
                                </div>
                                <div class="form-group">
                                    <label for="license_id" class="form-label">เลขที่ใบอนุญาตขายยา</label>
                                    <input type="text" id="license_id" class="form-input" placeholder="สำหรับร้านยา">
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-title">โลโก้ (Logo)</h3>
                            <p class="card-description">อัปโหลดโลโก้ร้านสำหรับหน้า Login และใบเสร็จ (แนะนำไฟล์ .png
                                พื้นหลังโปร่งใส)</p>
                            <div class="form-group-upload">
                                <div class="logo-preview-box">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                <input type="file" id="logo_upload" class="form-input-file" hidden>
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('logo_upload').click();">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    อัปโหลดโลโก้
                                </button>
                            </div>
                        </div>

                        <!-- [!!! CARD 3 - ADJUSTED !!!] การ์ดมาตรฐาน -->
                        <div class="settings-card">
                            <h3 class="card-title">มาตรฐานและการปฏิบัติตามข้อกำหนด</h3>
                            <p class="card-description">ตั้งค่าการเชื่อมต่อและเปิดใช้งานโหมดที่เกี่ยวข้องกับมาตรฐานร้านยาและกฎหมาย</p>

                            <!-- 1. Toggles (เหมือนเดิม) -->
                            <h4 class="form-section-title">การตั้งค่าเชิงระบบ (System Settings)</h4>
                            <div class="form-toggle-list">
                                <!-- PDPA -->
                                <div class="form-toggle-item">
                                    <span>
                                        <i class="fa-solid fa-shield-halved" style="color: #0071e3;"></i>
                                        <b>PDPA Mode:</b> เปิดใช้งานการปกปิดข้อมูลคนไข้
                                    </span>
                                    <label class="form-toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <!-- TMT -->
                                <div class="form-toggle-item">
                                    <span>
                                        <i class="fa-solid fa-database" style="color: #34c759;"></i>
                                        <b>TMT Integration:</b> เปิดใช้การอ้างอิงรหัสยา TMT
                                    </span>
                                    <label class="form-toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- [!!! NEW SECTION !!!] -->
                            <!-- 2. การ์ดรับรอง (แทนที่ roles-list เดิม) -->
                            <h4 class="form-section-title" style="margin-top: 24px;">ข้อมูลการรับรอง (Compliance Info)</h4>
                            <p class="card-description" style="margin-top: -12px; margin-bottom: 16px;">
                                ซอฟต์แวร์นี้ได้รับการพัฒนาโดยคำนึงถึงมาตรฐานต่อไปนี้:
                            </p>
                            
                            <!-- [REUSE] ใช้ .form-grid-2-col ที่มีอยู่ -->
                            <div class="form-grid-2-col">
                                <!-- การ์ด GPP -->
                                <div class="compliance-card">
                                    <div class="compliance-icon-wrapper" style="background-color: #e6f6e9;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShMbCDzNr7Me_KYOXQh-vb1mIpsiVGPAyrr1qORTKzWh6tB60O4LLbgbJ9LJnJm7k9cG4&usqp=CAU" style="border-radius: 60%; object-fit: cover;">
                                    </div>
                                    <h5 class="compliance-title">GPP (Good Pharmacy Practice)</h5>
                                    <p class="compliance-description">
                                        รองรับการทำงานตามมาตรฐานร้านยาคุณภาพ (เช่น การติดตามวันหมดอายุ, การจัดการสต็อก)
                                    </p>
                                </div>
                                
                                <!-- การ์ด ISO -->
                                <div class="compliance-card">
                                    <div class="compliance-icon-wrapper" style="background-color: #e5f1ff;">
                                        <i class="fa-solid fa-award" style="color: #0071e3;"></i>
                                    </div>
                                    <h5 class="compliance-title">ISO/IEC 29110</h5>
                                    <p class="compliance-description">
                                        กระบวนการพัฒนาซอฟต์แวร์เป็นไปตามมาตรฐานวิศวกรรมซอฟต์แวร์
                                    </p>
                                </div>
                            </div>
                            <!-- [!!! END NEW SECTION !!!] -->

                        </div>


                        <div class="form-actions">
                            <button class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </section>

                    <!-- ======================= -->
                    <!--  แท็บ 2: ผู้ใช้งานและสิทธิ์  -->
                    <!-- ======================= -->
                    <section id="tab-users" class="settings-pane">

                    <div class="settings-card">
                        <h3 class="card-title">การจัดการบทบาท (Roles Management)</h3>
                        <p class="card-description">สร้างบทบาทของพนักงานเพื่อกำหนดสิทธิ์การเข้าถึงส่วนต่างๆ</p>

                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Admin</strong>
                                    <small>ทำได้ทุกอย่าง รวมถึงการตั้งค่าและรายงาน</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Pharmacist</strong>
                                    <small>ขาย, จัดการสต็อก, รับของเข้า (จัดการยา)</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Staff</strong>
                                    <small>ทำได้แค่ขาย (POS) และดูสต็อก</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                        </div>
                        <button class="btn btn-secondary" style="margin-top: 1rem;">
                            <i class="fa-solid fa-plus"></i> เพิ่มบทบาทใหม่
                        </button>
                    </div>

                    <div class="settings-card">
                        
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">การจัดการผู้ใช้งาน (User Management)</h3>
                                <p class="card-description">เพิ่ม ลบ และแก้ไขบัญชีผู้ใช้งานระบบ</p>
                            </div>
                            <div>
                                <button class="btn btn-primary">
                                    <i class="fa-solid fa-user-plus"></i>
                                    เพิ่มผู้ใช้ใหม่
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive-wrapper">
                            <table class="settings-table">
                                <thead>
                                    <tr>
                                        <th>ชื่อผู้ใช้งาน</th>
                                        <th>บทบาท (Role)</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">A</div>
                                                <div class="user-name-email">
                                                    <strong>Admin User</strong>
                                                    <small>admin@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role" style="background-color: #ffe6e6; color: #d90000;">Admin</span>
                                        </td>
                                        <td>
                                            <span class="badge-status active">Active</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">P</div>
                                                <div class="user-name-email">
                                                    <strong>Pharma One</strong>
                                                    <small>pharma1@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role" style="background-color: #e6f6e9; color: #008a1e;">Pharmacist</span>
                                        </td>
                                        <td>
                                            <span class="badge-status active">Active</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">S</div>
                                                <div class="user-name-email">
                                                    <strong>Staff Member</strong>
                                                    <small>staff@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role">Staff</span>
                                        </td>
                                        <td>
                                            <span class="badge-status inactive">Inactive</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                    <!-- ======================= -->
                    <!--     แท็บ 3: การขาย (POS)    -->
                    <!-- ======================= -->
                    <section id="tab-pos" class="settings-pane">

                    <div class="settings-card">
                         <h3 class="card-title">ข้อมูลการขาย (POS Details)</h3>
                            <p class="card-description">ข้อมูลการขาย POS ของคุณ</p>
                        <h3 class="card-title">ภาษี (Tax)</h3>
                        <div class="form-group">
                            <label for="vat_rate" class="form-label">อัตราภาษีมูลค่าเพิ่ม (VAT)</label>
                            <div class="input-with-suffix">
                                <input type="number" id="vat_rate" class="form-input" value="7">
                                <span>%</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">การแสดงราคา</label>
                            <div class="form-radio-group">
                                <label class="form-radio-label">
                                    <input type="radio" name="price_display" value="inclusive" checked>
                                    <span>ราคารวมภาษีแล้ว (Vat Included)</span>
                                </label>
                                <label class="form-radio-label">
                                    <input type="radio" name="price_display" value="exclusive">
                                    <span>ราคาไม่รวมภาษี (Vat Excluded)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">ช่องทางชำระเงิน (Payment Methods)</h3>
                        <p class="card-description">เปิด/ปิด ช่องทางที่จะแสดงในหน้า POS</p>
                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-money-bill-wave"></i> เงินสด (Cash)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-qrcode"></i> โอนผ่าน QR (QR Payment)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-credit-card"></i> บัตรเครดิต (Credit Card)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">ฮาร์ดแวร์ และการเชื่อมต่อ (Hardware & Connection)</h3>
                        <p class="card-description">ตั้งค่าการเชื่อมต่อเครื่องพิมพ์ใบเสร็จและลิ้นชักเก็บเงินสำหรับหน้า POS</p>

                        <h4 class="form-section-title">เครื่องพิมพ์ใบเสร็จ (Receipt Printer)</h4>
                        
                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="printer_connection" class="form-label">ประเภทการเชื่อมต่อ</label>
                                <select id="printer_connection" class="form-select">
                                    <option value="none">ไม่พิมพ์ (Disable)</option>
                                    <option value="browser" selected>พิมพ์ผ่าน Browser (Browser Print)</option>
                                    <option value="network">เครื่องพิมพ์เครือข่าย (Network Printer)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="printer_paper_size" class="form-label">ขนาดกระดาษ</label>
                                <select id="printer_paper_size" class="form-select">
                                    <option value="80mm" selected>80mm (มาตรฐาน)</option>
                                    <option value="58mm">58mm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="printer_ip" class="form-label">IP Address เครื่องพิมพ์ (ถ้ามี)</label>
                            <input type="text" id="printer_ip" class="form-input" placeholder="เช่น 192.168.1.100">
                            <p class="form-label-description" style="margin-top: 8px;">*จำเป็น เมื่อเลือกประเภท "Network Printer"</p>
                        </div>
                        
                        <h4 class="form-section-title">การทำงานอัตโนมัติ (Automation)</h4>

                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-print"></i> พิมพ์ใบเสร็จอัตโนมัติเมื่อจบการขาย</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-cash-register"></i> เปิดลิ้นชักเก็บเงินอัตโนมัติ (เมื่อชำระเงินสด)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">การตั้งค่าใบเสร็จ (Receipt Template)</h3>
                        <div class="form-group">
                            <label for="receipt_header" class="form-label">ข้อความส่วนหัวใบเสร็จ</label>
                            <textarea id="receipt_header" class="form-textarea" rows="2" placeholder="เช่น 'ขอบคุณที่ใช้บริการ'"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="receipt_footer" class="form-label">ข้อความส่วนท้ายใบเสร็จ</label>
                            <textarea id="receipt_footer" class="form-textarea" rows="2"
                                placeholder="เช่น 'กรุณาตรวจสอบสินค้าก่อนออกจากร้าน'"></textarea>
                        </div>
                    </div>
                </section>

                <section id="tab-inventory" class="settings-pane">

                    <div class="settings-card">
                        
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">หมวดหมู่สินค้า (Product Categories)</h3>
                                <p class="card-description">จัดกลุ่มสินค้าและยาเพื่อง่ายต่อการจัดการและดูรายงาน</p>
                            </div>
                            <div>
                                <button class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i>
                                    เพิ่มหมวดหมู่ใหม่
                                </button>
                            </div>
                        </div>
                        
                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>ยาอันตราย</strong>
                                    <small>ยาที่ต้องจ่ายโดยเภสัชกร (เช่น ยาปฏิชีวนะ)</small>
                                </div>
                                <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>ยาควบคุมพิเศษ</strong>
                                    <small>ยาที่ต้องมีใบสั่งแพทย์</small>
                                </div>
                                <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>ยาสามัญประจำบ้าน</strong>
                                    <small>ขายได้ทั่วไป (เช่น พาราเซตามอล)</small>
                                </div>
                                <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>เวชภัณฑ์ / อุปกรณ์</strong>
                                    <small>เช่น สำลี, ผ้าพันแผล, หน้ากากอนามัย</small>
                                </div>
                                <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">หน่วยนับ (Units of Measurement)</h3>
                                <p class="card-description">
                                    <strong>สำคัญมาก:</strong> กำหนดความสัมพันธ์ของหน่วยนับ (เช่น กล่อง -> แผง -> เม็ด)
                                </p>
                            </div>
                            <div>
                                <button class="btn btn-secondary">
                                    <i class="fa-solid fa-plus"></i>
                                    เพิ่มหน่วยนับใหม่
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive-wrapper">
                            <table class="settings-table">
                                <thead>
                                    <tr>
                                        <th>หน่วยใหญ่ (เช่น กล่อง)</th>
                                        <th>หน่วยเล็ก (เช่น แผง)</th>
                                        <th>อัตราส่วน</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>กล่อง</strong> (Box)</td>
                                        <td>แผง (Panel)</td>
                                        <td>1 กล่อง = 10 แผง</td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>แผง</strong> (Panel)</td>
                                        <td>เม็ด (Pill)</td>
                                        <td>1 แผง = 10 เม็ด</td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>ขวด</strong> (Bottle)</td>
                                        <td>มิลลิลิตร (ml)</td>
                                        <td>1 ขวด = 500 ml</td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>โหล</strong> (Dozen)</td>
                                        <td>ชิ้น (Piece)</td>
                                        <td>1 โหล = 12 ชิ้น</td>
                                        <td>
                                            <button class="btn-icon" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section id="tab-pharmacy" class="settings-pane">

                    <div class="settings-card">
                        <h3 class="card-title">การตั้งค่าคนไข้และสมาชิก</h3>
                        <p class="card-description">ตั้งค่าระบบสะสมแต้ม (Loyalty Program) และข้อมูลที่จำเป็นสำหรับคนไข้</p>

                        <h4 class="form-section-title">ระบบสะสมแต้ม (Loyalty Program)</h4>
                        
                        <div class="form-toggle-list" style="margin-bottom: 24px;">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-star"></i> เปิดใช้งานระบบสะสมแต้ม</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="loyalty_earn_rate" class="form-label">ทุกๆ (บาท) ที่ใช้จ่าย</label>
                                <input type="number" id="loyalty_earn_rate" class="form-input" value="25">
                            </div>
                            <div class="form-group">
                                <label for="loyalty_earn_points" class="form-label">จะได้รับ (แต้ม)</label>
                                <input type="number" id="loyalty_earn_points" class="form-input" value="1">
                            </div>
                        </div>

                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="loyalty_redeem_points" class="form-label">อัตราแลกแต้ม (แต้ม)</label>
                                <input type="number" id="loyalty_redeem_points" class="form-input" value="100">
                            </div>
                            <div class="form-group">
                                <label for="loyalty_redeem_value" class="form-label">มีมูลค่า (บาท)</label>
                                <div class="input-with-suffix">
                                    <input type="number" id="loyalty_redeem_value" class="form-input" value="10">
                                    <span>บาท</span>
                                </div>
                            </div>
                        </div>

                        <h4 class="form-section-title">ข้อมูลคนไข้ (Patient Profile)</h4>
                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-triangle-exclamation"></i> บังคับกรอกประวัติการแพ้ยา (Allergies)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-heart-pulse"></i> บังคับกรอกโรคประจำตัว (Chronic Conditions)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked >
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">การตั้งค่าฉลากยา (Drug Label)</h3>
                        <p class="card-description">กำหนดข้อความเริ่มต้นและคลังวิธีใช้ยา สำหรับพิมพ์ฉลากแปะซองยา</p>

                        <div class="form-group">
                            <label for="label_header" class="form-label">ข้อความหัวฉลาก (เริ่มต้น)</label>
                            <textarea id="label_header" class="form-textarea" rows="2" placeholder="เช่น 'ยานี้สำหรับคุณ [Patient Name]'"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="label_footer" class="form-label">ข้อความท้ายฉลาก (เริ่มต้น)</label>
                            <textarea id="label_footer" class="form-textarea" rows="2" placeholder="เช่น 'เก็บให้พ้นมือเด็ก' หรือ 'หากมีอาการแพ้ ให้หยุดยาทันที'"></textarea>
                        </div>

                        <h4 class="form-section-title">คลังวิธีใช้ยา (Standard Dosing Instructions)</h4>
                        <p class="card-description" style="margin-top: -12px; margin-bottom: 16px;">
                            เพิ่ม/ลบ วิธีใช้ยาที่พบบ่อย เพื่อให้เภสัชกรเลือกใช้ได้อย่างรวดเร็ว
                        </p>

                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info"><strong>รับประทานครั้งละ 1 เม็ด หลังอาหาร เช้า-กลางวัน-เย็น</strong></div>
                                <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>รับประทานครั้งละ 1 เม็ด หลังอาหาร เช้า-เย็น</strong></div>
                                <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>รับประทานครั้งละ 1 เม็ด ก่อนนอน</strong></div>
                                <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>ทาบริเวณที่เป็น วันละ 2 ครั้ง เช้า-เย็น</strong></div>
                                <button class="btn-icon" title="ลบ" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>

                        <button class="btn btn-secondary" style="margin-top: 1rem;">
                            <i class="fa-solid fa-plus"></i> เพิ่มวิธีใช้ใหม่
                        </button>
                    </div>

                </section>


                    <!-- ======================= -->
                    <!-- แท็บ 4: ระบบและการแจ้งเตือน -->
                    <!-- ======================= -->
                    <section id="tab-system" class="settings-pane">
                        <div class="settings-card">
                            <h3 class="card-title">การแจ้งเตือน (Alerts & Notifications)</h3>

                            <div class="form-group">
                                <label for="low_stock_alert" class="form-label">แจ้งเตือนสต็อกต่ำ (Low Stock
                                    Alert)</label>
                                <p class="form-label-description">เตือนเมื่อสินค้าเหลือต่ำกว่าจำนวนที่กำหนด</p>
                                <div class="input-with-suffix">
                                    <input type="number" id="low_stock_alert" class="form-input" value="10">
                                    <span>ชิ้น</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="expiry_alert" class="form-label">แจ้งเตือนวันหมดอายุ (Expiry
                                    Alert)</label>
                                <p class="form-label-description">เตือนล่วงหน้าก่อนที่ยาจะหมดอายุ (สำคัญมาก)</p>
                                <div class="input-with-suffix">
                                    <input type="number" id="expiry_alert" class="form-input" value="90">
                                    <span>วัน</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-title">การตั้งค่าทั่วไป (General)</h3>
                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="timezone" class="form-label">เขตเวลา (Timezone)</label>
                                    <select id="timezone" class="form-select">
                                        <option value="Asia/Bangkok" selected>Asia/Bangkok (GMT+7)</option>
                                        <option value="Europe/London">Europe/London (GMT+0)</option>
                                        <option value="America/New_York">America/New_York (GMT-5)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency" class="form-label">สกุลเงิน (Currency)</label>
                                    <select id="currency" class="form-select">
                                        <option value="THB" selected>THB (฿)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="JPY">JPY (¥)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
</x-app-layout>
