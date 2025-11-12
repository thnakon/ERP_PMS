<!-- =================================================== -->
<!--               Support Modal (สไตล์ Apple)             -->
<!-- =================================================== -->
<!-- 1. Overlay (ฉากหลังแบบเบลอ) -->
<div class="support-modal-overlay hidden" id="supportModalOverlay">

    <!-- 2. Content (กล่องเนื้อหา) -->
    <div class="support-modal-content">
        
        <!-- 3. ปุ่มปิด (มุมขวาบน) -->
        <button class="support-modal-close" id="closeSupportModalBtn" type="button" title="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- 4. ส่วนหัว -->
        <h2 class="support-modal-title">Need some Help?</h2>

        <!-- 5. รูปประกอบ (SVG Placeholder) -->
        <div class="support-modal-illustration">
            <!-- นี่คือ SVG ที่วาดขึ้นมาให้คล้ายกับในรูปของคุณ -->
            <svg width="240" height="120" viewBox="0 0 240 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="240" height="120" rx="12" fill="#F3F4F6"/>
                <path d="M120.5 56.002C128.52 56.002 135.034 50.1119 135.034 42.8601C135.034 35.6083 128.52 29.7183 120.5 29.7183C112.48 29.7183 105.966 35.6083 105.966 42.8601C105.966 50.1119 112.48 56.002 120.5 56.002Z" fill="#3B5998"/>
                <path d="M101.521 89.9999C101.521 82.2573 107.014 76.0141 113.883 76.0141H127.117C133.986 76.0141 139.479 82.2573 139.479 89.9999V90.2816H101.521V89.9999Z" fill="white"/>
                <path d="M110.334 57.7124C108.051 59.2088 106.12 61.3507 104.722 63.9056C103.324 66.4605 102.5 69.3409 102.5 72.2856V90H101.5V72.2856C101.5 69.1911 102.368 66.177 103.829 63.504C105.29 60.831 107.29 58.5915 109.67 56.9698L110.334 57.7124Z" fill="#3B5998" opacity="0.3"/>
                <path d="M149.208 81.7181L135.208 60.7181L136.792 59.2819L150.792 80.2819L149.208 81.7181Z" fill="#F9A825"/>
                <path d="M134 60.5H151V81.5H134V60.5Z" fill="#F9A825"/>
                <rect x="135" y="80" width="17" height="1" rx="0.5" fill="#E28E0D"/>
                <!-- กระถางซ้าย -->
                <rect x="42" y="70" width="16" height="30" rx="4" fill="#A5C3E3"/>
                <rect x="36" y="50" width="4" height="20" rx="2" fill="#D1E3F3"/>
                <rect x="44" y="38" width="4" height="32" rx="2" fill="#D1E3F3"/>
                <rect x="52" y="46" width="4" height="24" rx="2" fill="#D1E3F3"/>
                <!-- กระถางขวา -->
                <rect x="190" y="65" width="16" height="35" rx="4" fill="#A5C3E3"/>
                <rect x="184" y="40" width="4" height="30" rx="2" fill="#D1E3F3"/>
                <rect x="198" y="44" width="4" height="26" rx="2" fill="#D1E3F3"/>
            </svg>
        </div>

        <!-- 6. เนื้อหาและฟอร์ม -->
        <div class="support-modal-form">
            <p class="support-modal-description">
                Describe your question and our specialists will answer you within 24 hours.
            </p>

            <div class="form-group-modal">
                <label for="supportSubject" class="form-label-modal">Request Subject</label>
                <!-- [NEW] สไตล์ Select แบบ Minimal -->
                <div class="form-select-wrapper">
                    <select id="supportSubject" class="form-select-modal">
                        <option value="tech">Technical difficulties</option>
                        <option value="billing">Billing question</option>
                        <option value="feature">Feature request</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group-modal">
                <label for="supportDescription" class="form-label-modal">Description</label>
                <textarea id="supportDescription" class="form-textarea-modal" rows="4" 
                          placeholder="Add some description of the request"></textarea>
            </div>

            <button class="support-modal-button" type="button">Send Request</button>
        </div>
    </div>
</div>