<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pharmacy POS</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    </head>

    <body>

        <div class="pos-container">

            <!-- LEFT PANEL: Catalog -->
            <div class="pos-left-panel">
                {{-- HEADER --}}
                <div class="sr-header">
                    <div class="sr-header-left">
                        <p class="sr-breadcrumb">
    Dashboard / <span style="color: #3a3a3c; font-weight: 600;">Point of Sale</span>
</p>

                        <h2 class="sr-page-title">Point of Sale (POS)</h2>
                    </div>
                </div>

                <!-- Category Toggle -->
                <div class="pos-header">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div class="pos-category-slider" id="categorySlider">
                            <button class="pos-cat-btn active" data-cat="all">All Items</button>
                            <button class="pos-cat-btn" data-cat="medicine">Medicine</button>
                            <button class="pos-cat-btn" data-cat="liquid">Liquid/Syrup</button>
                            <button class="pos-cat-btn" data-cat="cream">Cream/Topical</button>
                            <button class="pos-cat-btn" data-cat="device">Devices</button>
                        </div>
                    </div>
                    <button class="pos-btn-save"
                        style="background-color: var(--pos-accent-blue); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); padding: 10px 20px; margin-left: 10px;">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </button>
                </div>

                <!-- Search Section -->
                <div class="pos-search-row">
                    <div class="pos-search-wrapper" style="flex: 2;">
                        <i class="fa-solid fa-magnifying-glass pos-search-icon"></i>
                        <input type="text" class="pos-search-input" id="productSearchInput"
                            placeholder="Search Name, Generic Name, Barcode, Symptom...">
                    </div>
                    <div class="pos-search-wrapper" style="flex: 1;">
                        <i class="fa-solid fa-user pos-search-icon"></i>
                        <input type="text" class="pos-search-input" placeholder="Customer Phone / ID">
                        <i class="fa-solid fa-qrcode"
                            style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--pos-text-secondary); cursor: pointer;"></i>
                    </div>
                </div>

                <!-- Product Grid (12 Items) -->
                <div class="pos-product-grid" id="productGrid">
                    <!-- 1 -->
                    <div class="pos-product-card" data-category="medicine" onclick="addToCart('Tylenol 500mg', 120)">
                        <span class="pos-badge drug">PARA</span>
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-pills"
                                style="font-size: 3rem; color: #3b82f6;"></i></div>
                        <div class="pos-prod-name">Tylenol 500mg</div>
                        <div class="pos-prod-price">฿120.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 2 -->
                    <div class="pos-product-card" data-category="cream" onclick="addToCart('Bacitracin Zinc', 250)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-pump-soap"
                                style="font-size: 3rem; color: #f59e0b;"></i></div>
                        <div class="pos-prod-name">Bacitracin Zinc</div>
                        <div class="pos-prod-price">฿250.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 3 -->
                    <div class="pos-product-card" data-category="liquid" onclick="addToCart('Cough Syrup', 85)">
                        <span class="pos-badge expiry">FEFO Alert</span>
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-bottle-droplet"
                                style="font-size: 3rem; color: #ef4444;"></i></div>
                        <div class="pos-prod-name">Cough Syrup</div>
                        <div class="pos-prod-price">฿85.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 4 -->
                    <div class="pos-product-card" data-category="device" onclick="addToCart('Thermometer', 590)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-temperature-high"
                                style="font-size: 3rem; color: #10b981;"></i></div>
                        <div class="pos-prod-name">Digital Thermometer</div>
                        <div class="pos-prod-price">฿590.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 5 -->
                    <div class="pos-product-card" data-category="medicine" onclick="addToCart('Amoxicillin 500mg', 45)">
                        <span class="pos-badge drug">ANTIBIOTIC</span>
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-capsules"
                                style="font-size: 3rem; color: #8b5cf6;"></i></div>
                        <div class="pos-prod-name">Amoxicillin 500mg</div>
                        <div class="pos-prod-price">฿45.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 6 -->
                    <div class="pos-product-card" data-category="medicine" onclick="addToCart('Vitamin C 1000mg', 300)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-lemon"
                                style="font-size: 3rem; color: #facc15;"></i></div>
                        <div class="pos-prod-name">Vitamin C 1000mg</div>
                        <div class="pos-prod-price">฿300.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 7 -->
                    <div class="pos-product-card" data-category="liquid" onclick="addToCart('Alcohol Gel', 60)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-hand-holding-droplet"
                                style="font-size: 3rem; color: #3b82f6;"></i></div>
                        <div class="pos-prod-name">Alcohol Gel 75%</div>
                        <div class="pos-prod-price">฿60.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 8 -->
                    <div class="pos-product-card" data-category="device" onclick="addToCart('Face Mask (Box)', 100)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-mask-face"
                                style="font-size: 3rem; color: #9ca3af;"></i></div>
                        <div class="pos-prod-name">Face Mask (Box)</div>
                        <div class="pos-prod-price">฿100.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 9 -->
                    <div class="pos-product-card" data-category="device" onclick="addToCart('Bandage Roll', 35)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-bandage"
                                style="font-size: 3rem; color: #ec4899;"></i></div>
                        <div class="pos-prod-name">Bandage Roll</div>
                        <div class="pos-prod-price">฿35.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 10 -->
                    <div class="pos-product-card" data-category="cream" onclick="addToCart('Aloe Vera Gel', 150)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-leaf"
                                style="font-size: 3rem; color: #4ade80;"></i></div>
                        <div class="pos-prod-name">Aloe Vera Gel</div>
                        <div class="pos-prod-price">฿150.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 11 -->
                    <div class="pos-product-card" data-category="liquid" onclick="addToCart('Eye Drops', 90)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-eye"
                                style="font-size: 3rem; color: #60a5fa;"></i></div>
                        <div class="pos-prod-name">Eye Drops</div>
                        <div class="pos-prod-price">฿90.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                    <!-- 12 -->
                    <div class="pos-product-card" data-category="cream" onclick="addToCart('Baby Powder', 55)">
                        <div class="pos-prod-img-wrapper"><i class="fa-solid fa-baby"
                                style="font-size: 3rem; color: #f472b6;"></i></div>
                        <div class="pos-prod-name">Baby Powder</div>
                        <div class="pos-prod-price">฿55.00</div>
                        <div class="pos-add-btn">+</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Cart & Payment -->
            <div class="pos-right-panel">
                <!-- Cart Header -->
                <div class="pos-cart-header">
                    <div class="pos-cart-title"><i class="fa-solid fa-cart-shopping"></i> Overview</div>
                    <div class="pos-cart-date" id="currentDate"></div>
                </div>

                <!-- Column Headers -->
                <div
                    style="display: grid; grid-template-columns: 1.5fr 1fr 0.8fr; font-size: 0.8rem; color: #aaa; margin-bottom: 10px; padding-right: 4px;">
                    <div>Product</div>
                    <div style="text-align:center;">Qty</div>
                    <div style="text-align:right;">Price</div>
                </div>

                <!-- Items List -->
                <div class="pos-cart-items" id="cartItemsContainer">
                    <div style="text-align: center; color: #ccc; margin-top: 40px;">
                        <i class="fa-solid fa-cart-arrow-down" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>Cart is empty</p>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="pos-payment-info">
                    <div class="pos-pay-row">
                        <span>Net Total</span>
                        <span id="netTotalDisplay">0.00</span>
                    </div>
                    <div class="pos-pay-row">
                        <span>Discount</span>
                        <span id="discount-trigger" onclick="openModal('discountModal')"
                            style="color: var(--pos-accent-blue); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            + Coupon
                        </span>
                    </div>
                    <div class="pos-pay-row total">
                        <span><i class="fa-solid fa-coins"></i> Total Price</span>
                        <span id="grandTotalDisplay">0 Baht</span>
                    </div>
                </div>

                <!-- Payment Type Grid -->
                <div style="font-size: 0.9rem; font-weight: 600; margin-bottom: 10px;">Payment Type</div>
                <div class="pos-methods-grid">
                    <div class="pos-method-card selected" data-method="cash" onclick="selectMethod(this)">
    <i class="fa-solid fa-money-bill-wave" style="color: #28a745;"></i>
</div>

                    <!-- [!!! METHOD: CREDIT CARD !!!] -->
                    <div class="pos-method-card" data-method="credit" onclick="selectMethod(this)"><i
                            class="fa-brands fa-cc-mastercard"></i></div>
                    <!-- [!!! METHOD: QR CODE !!!] -->
                    <div class="pos-method-card" data-method="qrcode" onclick="selectMethod(this)"><i
                            class="fa-solid fa-qrcode"></i></div>
                    <div class="pos-method-card" data-method="paypal" onclick="selectMethod(this)"><i
                            class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="pos-method-card" data-method="alipay" onclick="selectMethod(this)"><i
                            class="fa-brands fa-alipay"></i></div>
                    <div class="pos-method-card" data-method="truemoney" onclick="selectMethod(this)">
                        <img src="/images/truelogo.png" style="width: 48px; height: auto; margin-bottom: 0px;">
                    </div>

                </div>

                <!-- Actions -->
                <div class="pos-actions">
                    <button class="pos-btn-reset">Hold Bill</button>
                    <button class="pos-btn-save" onclick="handlePayment()"><i class="fa-solid fa-check"></i> Pay &
                        Print</button>
                </div>
            </div>

        </div>

        <!-- MODAL 1: DISCOUNT -->
        <div class="pos-modal-overlay" id="discountModal">
            <div class="pos-modal">
                <div class="pos-modal-header">
                    <div class="pos-modal-title">Discount <i class="fa-solid fa-tag" style="font-size: 1.1rem;"></i>
                    </div>
                    <button style="background:none;border:none;cursor:pointer;"
                        onclick="closeModal('discountModal')"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="pos-modal-body">
                    <div class="pos-input-group">
                        <label class="pos-input-label">Coupon Code</label>
                        <div class="pos-input-wrapper">
                            <input type="text" class="pos-input-box" id="couponInput" placeholder="OBOUWARM">
                            <i class="fa-solid fa-ticket pos-input-icon"></i>
                        </div>
                    </div>
                    <div class="pos-modal-actions">
                        <button class="pos-btn-clear" onclick="clearCoupon()">Clear</button>
                        <button class="pos-btn-use" onclick="applyCoupon()">Use</button>
                    </div>
                    <div class="pos-modal-footer-info">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-percent"
                                style="border: 2px solid; padding: 4px; border-radius: 6px; font-size: 0.8rem;"></i>
                            Discount
                        </div>
                        <span style="font-size: 1.2rem;">15%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- [!!! MODAL 2: QR PAYMENT !!!] -->
        <div class="pos-modal-overlay" id="paymentQrModal">
            <div class="pos-modal">
                <div class="pos-modal-header">
                    <div class="pos-modal-title">Payment <i class="fa-solid fa-wallet"></i></div>
                    <button style="background:none;border:none;cursor:pointer;"
                        onclick="closeModal('paymentQrModal')"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="pos-modal-body pay-modal-content">
                    <div class="pay-modal-title-blue">Pay with PromptPay</div>

                    <!-- QR Frame -->
                    <div class="qr-frame">
                        <div class="qr-image-container">
                            <!-- Ensure this path is correct in your public folder -->
                            <img src="{{ asset('images/TESTQR.JPG') }}" alt="QR Code" class="qr-image">
                        </div>
                        <div style="font-size:0.9rem; color:#1d1d1f; margin-bottom:4px;">Thanakorn Duangkumwattanasiri
                        </div>
                        <div class="qr-timer" id="qrTimer">5 MIN : 05 SEC</div>
                        <div class="qr-timer-sub">Pay on time</div>
                    </div>

                    <button class="pay-btn-confirm" id="btnConfirmQr">
                        Payment <span class="dynamic-total">0.0</span> Baht
                    </button>
                </div>
            </div>
        </div>

        <!-- [!!! MODAL 3: CREDIT CARD PAYMENT !!!] -->
        <div class="pos-modal-overlay" id="paymentCreditModal">
            <div class="pos-modal">
                <div class="pos-modal-header">
                    <div class="pos-modal-title">Payment <i class="fa-solid fa-credit-card"></i></div>
                    <button style="background:none;border:none;cursor:pointer;"
                        onclick="closeModal('paymentCreditModal')"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="pos-modal-body pay-modal-content">
                    <div class="pay-modal-title-blue" style="text-align:left; margin-bottom:15px;">Payment via credit
                        card</div>

                    <div class="cc-form">
                        <div class="pos-input-group">
                            <label class="pos-input-label">Card Number</label>
                            <div class="pos-input-wrapper">
                                <input type="text" class="pos-input-box cc" placeholder="1234 1234 1234 1234">
                                <div class="cc-icon-right">
                                    <i class="fa-brands fa-cc-visa" style="color:#1a1f71; margin-right:5px;"></i>
                                    <i class="fa-brands fa-cc-mastercard" style="color:#eb001b;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pos-input-group">
                            <label class="pos-input-label">Name</label>
                            <input type="text" class="pos-input-box" value="Thanakorn Duangkumwattanasiri">
                        </div>
                        <div class="cc-row">
                            <div class="cc-col">
                                <label class="pos-input-label">Expiration Date</label>
                                <div class="pos-input-wrapper">
                                    <input type="text" class="pos-input-box" value="04/11/2028">
                                    <i class="fa-regular fa-calendar pos-input-icon"></i>
                                </div>
                            </div>
                            <div class="cc-col">
                                <label class="pos-input-label">CVC</label>
                                <div class="pos-input-wrapper">
                                    <input type="text" class="pos-input-box" value="293">
                                    <i class="fa-regular fa-credit-card pos-input-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="pay-btn-confirm" id="btnConfirmCC">
                        Payment <span class="dynamic-total">0.0</span> Baht
                    </button>

                    <div style="margin-top:15px; font-size:0.8rem; color:#86868b; text-align:center;">
                        <i class="fa-solid fa-shield-halved"></i> Pay securely with VISA/Mastercard<br>
                        Powered by Thakaon
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/pos.js') }}"></script>
    </body>

    </html>
</x-app-layout>
