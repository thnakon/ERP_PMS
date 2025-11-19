document.addEventListener('DOMContentLoaded', function() {
    
    // --- State ---
    let cart = [];
    let currentDiscountPercent = 0;
    let selectedPaymentMethod = 'cash'; // Default
    let currentGrandTotal = 0; // Track total for modals
    let timerInterval;

    // --- Slider Logic ---
    const slider = document.getElementById('categorySlider');
    if(slider) {
        const buttons = slider.querySelectorAll('.pos-cat-btn');
        const activeButton = slider.querySelector('.pos-cat-btn.active');
        if (activeButton) updateSlider(slider, activeButton);

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateSlider(slider, btn);
                filterProducts(btn.dataset.cat);
            });
        });
        setTimeout(() => slider.classList.add('slider-ready'), 100);
    }

    function updateSlider(wrapper, targetBtn) {
        const wrapperRect = wrapper.getBoundingClientRect();
        const btnRect = targetBtn.getBoundingClientRect();
        wrapper.style.setProperty('--slider-left', `${btnRect.left - wrapperRect.left}px`);
        wrapper.style.setProperty('--slider-width', `${btnRect.width}px`);
    }

    function filterProducts(category) {
        const products = document.querySelectorAll('.pos-product-card');
        products.forEach(card => {
            const cardCat = card.dataset.category;
            if (category === 'all' || cardCat === category) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // --- Cart Logic ---
    window.addToCart = function(name, price) {
        const existingItem = cart.find(item => item.name === name);
        if (existingItem) {
            existingItem.qty++;
        } else {
            cart.push({ name: name, price: parseFloat(price), qty: 1 });
        }
        renderCart();
    }

    window.updateCartQty = function(name, change) {
        const itemIndex = cart.findIndex(item => item.name === name);
        if (itemIndex > -1) {
            cart[itemIndex].qty += change;
            if (cart[itemIndex].qty <= 0) cart.splice(itemIndex, 1);
        }
        renderCart();
    }

    function renderCart() {
        const cartContainer = document.getElementById('cartItemsContainer');
        cartContainer.innerHTML = '';
        let netTotal = 0;

        if(cart.length === 0) {
            cartContainer.innerHTML = `
                <div style="text-align: center; color: #ccc; margin-top: 40px;">
                    <i class="fa-solid fa-cart-arrow-down" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>Cart is empty</p>
                </div>`;
        }

        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            netTotal += itemTotal;
            const el = document.createElement('div');
            el.className = 'pos-cart-item';
            el.innerHTML = `
                <div>
                    <div class="pos-item-name">${item.name}</div>
                    <div class="pos-item-unit">Unit</div>
                </div>
                <div class="pos-qty-ctrl">
                    <div class="pos-qty-btn" onclick="updateCartQty('${item.name}', -1)">-</div>
                    <span style="font-weight: 600; min-width: 20px; text-align: center;">${item.qty}</span>
                    <div class="pos-qty-btn" onclick="updateCartQty('${item.name}', 1)">+</div>
                </div>
                <div class="pos-item-price">฿${itemTotal.toLocaleString()}</div>
            `;
            cartContainer.appendChild(el);
        });

        updateTotals(netTotal);
    }

    function updateTotals(netTotal) {
        const discountAmount = netTotal * (currentDiscountPercent / 100);
        currentGrandTotal = netTotal - discountAmount; // Update global var

        const netTotalDisplay = document.getElementById('netTotalDisplay');
        const grandTotalDisplay = document.getElementById('grandTotalDisplay');
        
        if(netTotalDisplay) {
            netTotalDisplay.textContent = netTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if(grandTotalDisplay) {
            grandTotalDisplay.textContent = Math.ceil(currentGrandTotal).toLocaleString() + ' Baht';
        }
    }

    // --- Payment Method Selection ---
    window.selectMethod = function(element) {
        const methods = document.querySelectorAll('.pos-method-card');
        methods.forEach(m => m.classList.remove('selected'));
        element.classList.add('selected');
        selectedPaymentMethod = element.dataset.method;
    }

    // --- [!!! HANDLE PAY & PRINT !!!] ---
    window.handlePayment = function() {
        if(cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        // Update price on buttons
        const totalText = Math.ceil(currentGrandTotal).toLocaleString();
        const btnSpans = document.querySelectorAll('.dynamic-total');
        btnSpans.forEach(span => span.textContent = totalText);

        if (selectedPaymentMethod === 'qrcode') {
            openModal('paymentQrModal');
            startQrTimer();
        } else if (selectedPaymentMethod === 'credit') {
            openModal('paymentCreditModal');
        } else {
            alert('Payment success! (Cash/Other)');
            // Clear cart logic here if needed
        }
    }

    function startQrTimer() {
        const timerEl = document.getElementById('qrTimer');
        let duration = 305; // 5 min 05 sec in seconds
        
        if(timerInterval) clearInterval(timerInterval);

        function updateTimerDisplay() {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            timerEl.textContent = `${minutes} MIN : ${seconds < 10 ? '0' : ''}${seconds} SEC`;
        }

        updateTimerDisplay();
        timerInterval = setInterval(() => {
            duration--;
            if(duration < 0) {
                clearInterval(timerInterval);
                timerEl.textContent = "EXPIRED";
                timerEl.style.color = "red";
            } else {
                updateTimerDisplay();
            }
        }, 1000);
    }

    // --- Clear/Discount Logic ---
    const holdBtn = document.querySelector('.pos-btn-reset');
    if(holdBtn) {
        holdBtn.addEventListener('click', () => {
            if(cart.length > 0 && confirm('Clear current bill?')) {
                cart = [];
                currentDiscountPercent = 0;
                renderCart();
                const trigger = document.getElementById('discount-trigger');
                if(trigger) {
                     trigger.innerHTML = `+ Coupon`;
                     trigger.style.color = 'var(--pos-accent-blue)';
                     trigger.style.fontWeight = '600';
                     trigger.style.cursor = 'pointer';
                     trigger.onclick = function() { openModal('discountModal'); };
                }
            }
        });
    }

    window.clearCoupon = function() { document.getElementById('couponInput').value = ''; }
    window.applyCoupon = function() {
        const input = document.getElementById('couponInput');
        const code = input.value.trim();
        if(code) {
            currentDiscountPercent = 15;
            const trigger = document.getElementById('discount-trigger');
            if(trigger) {
                trigger.innerHTML = `<i class="fa-solid fa-circle-check"></i> 15% (${code})`;
                trigger.style.color = '#2ecc71'; 
                trigger.onclick = null;
            }
            closeModal('discountModal');
            renderCart(); 
        } else {
            alert('Please enter a coupon code');
        }
    }

    // --- Helpers ---
    const searchInput = document.getElementById('productSearchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.pos-product-card');
            products.forEach(card => {
                const name = card.querySelector('.pos-prod-name').textContent.toLowerCase();
                if (name.includes(term)) { card.style.display = 'flex'; } else { card.style.display = 'none'; }
            });
        });
    }

    function updateTime() {
        const now = new Date();
        const dateString = now.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        const dateEl = document.getElementById('currentDate');
        if(dateEl) dateEl.textContent = `${dateString} ${timeString}`;
        const modalDate = document.getElementById('modalDate');
        if(modalDate) modalDate.textContent = `${dateString} at ${timeString}`;
    }
    setInterval(updateTime, 1000);
    updateTime();
    
    renderCart(); // Init
});

// --- Global Modal Functions ---
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('show');
}
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('show');
}
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('pos-modal-overlay') || e.target.classList.contains('inv-modal-overlay')) {
        e.target.classList.remove('show');
    }
});