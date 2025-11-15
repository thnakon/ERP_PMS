{{-- 
  ลิงก์ไปยัง CSS และ JS ของ Footer
  (คุณอาจจะต้องปรับ path ให้ถูกต้องตามโครงสร้างโปรเจกต์)
--}}
<link rel="stylesheet" href="{{ asset('resources/css/footer.css') }}">

<footer class="apple-footer-container">
    <div class="footer-content-wrapper">
        
        {{-- ส่วนที่ 1: ลิงก์ (ซ้าย) --}}
        <div class="footer-links">
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Terms of Use</a>
            <a href="#" class="footer-link">Support</a>
        </div>

        {{-- ส่วนที่ 2: เครดิต (ขวา) --}}
        <div class="footer-credits">
            <span class="footer-copyright">
                Copyright © {{ date('Y') }} Your Pharmacy Name. All rights reserved.
            </span>
            <span class="footer-powered-by">
                Powered by <strong>Oboun</strong>
            </span>
        </div>

    </div>
</footer>

{{-- 
  โหลดไฟล์ JS (ถ้ามี)
  (คุณอาจจะต้องปรับ path ให้ถูกต้องตามโครงสร้างโปรเจกต์)
--}}
<script src="{{ asset('resources/js/footer.js') }}"></script>