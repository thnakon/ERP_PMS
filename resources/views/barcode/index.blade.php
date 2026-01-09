@extends('layouts.app')

@section('title', __('barcode.scanner_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('barcode.page_subtitle') }}
        </p>
        <span>{{ __('barcode.scanner_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('barcode.labels') }}"
        class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph-bold ph-printer"></i>
        {{ __('barcode.labels_title') }}
    </a>
@endsection

@push('styles')
    <style>
        /* Make Quagga video/canvas fill the viewport container */
        #camera-container {
            height: 400px;
        }

        #camera-viewport {
            position: absolute !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100% !important;
            height: 100% !important;
        }

        #camera-viewport video,
        #camera-viewport canvas {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            min-width: 100% !important;
            min-height: 100% !important;
            width: auto !important;
            height: auto !important;
            object-fit: cover !important;
        }

        #camera-viewport canvas.drawingBuffer {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Scanner --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Scanner Card --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-barcode text-ios-blue text-xl"></i>
                    {{ __('barcode.scanner_title') }}
                </h3>

                {{-- Scan Input --}}
                <div class="relative mb-4">
                    <input type="text" id="barcode-input" autofocus
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 pl-14 pr-4 text-lg font-mono focus:ring-4 focus:ring-ios-blue/20 outline-none transition-all"
                        placeholder="{{ __('barcode.scan_placeholder') }}" onkeypress="handleBarcodeInput(event)">
                    <i
                        class="ph-bold ph-barcode absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none"></i>
                    <button type="button" onclick="lookupBarcode()" data-no-loading
                        class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-2 bg-ios-blue text-white font-semibold rounded-xl hover:brightness-110 transition">
                        <i class="ph-bold ph-magnifying-glass"></i>
                    </button>
                </div>

                {{-- Camera Scanner --}}
                <div class="mb-4">
                    <div class="flex items-center gap-3 mb-3">
                        <button type="button" onclick="toggleCamera()" id="camera-toggle-btn" data-no-loading
                            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph-bold ph-camera" id="camera-icon"></i>
                            <span id="camera-text">{{ __('barcode.start_camera') }}</span>
                        </button>
                        <span class="text-sm text-gray-500">{{ __('barcode.scan_instruction') }}</span>
                    </div>
                    <div id="camera-container"
                        class="hidden relative bg-gray-900 rounded-2xl overflow-hidden min-h-[400px]">
                        <div id="camera-viewport" class="w-full h-full min-h-[400px]"></div>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                            <div class="w-80 h-52 border-2 border-green-400 rounded-lg"
                                style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.4)"></div>
                        </div>
                        <div id="scan-loading"
                            class="hidden absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                            <div class="text-center text-white">
                                <i class="ph ph-spinner-gap animate-spin text-4xl mb-2"></i>
                                <p>{{ __('barcode.scanning') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Result --}}
            <div id="product-result" class="hidden">
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-green-500 text-xl"></i>
                            {{ __('barcode.product_info') }}
                        </h3>
                        <button type="button" onclick="clearResult()" data-no-loading
                            class="text-gray-400 hover:text-gray-600 transition">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="flex gap-6">
                        {{-- Product Image --}}
                        <div id="product-image-container"
                            class="w-32 h-32 rounded-2xl bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <i class="ph-fill ph-pill text-gray-300 text-4xl" id="product-placeholder-icon"></i>
                            <img id="product-image" src="" alt="" class="hidden w-full h-full object-cover">
                        </div>

                        {{-- Product Details --}}
                        <div class="flex-1">
                            <h4 id="product-name" class="text-xl font-bold text-gray-900 mb-1"></h4>
                            <p id="product-name-th" class="text-gray-500 mb-3"></p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                <div class="p-3 rounded-xl bg-gray-50">
                                    <span class="text-xs text-gray-500 block">SKU</span>
                                    <span id="product-sku" class="font-semibold text-gray-900 font-mono"></span>
                                </div>
                                <div class="p-3 rounded-xl bg-green-50">
                                    <span class="text-xs text-gray-500 block">{{ __('barcode.price') }}</span>
                                    <span id="product-price" class="font-bold text-green-600"></span>
                                </div>
                                <div class="p-3 rounded-xl bg-purple-50">
                                    <span class="text-xs text-gray-500 block">{{ __('barcode.member_price') }}</span>
                                    <span id="product-member-price" class="font-bold text-purple-600"></span>
                                </div>
                                <div class="p-3 rounded-xl bg-blue-50">
                                    <span class="text-xs text-gray-500 block">{{ __('barcode.stock') }}</span>
                                    <span id="product-stock" class="font-bold text-blue-600"></span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a id="product-link" href="#"
                                    class="px-4 py-2 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl transition flex items-center gap-2">
                                    <i class="ph-bold ph-eye"></i>
                                    {{ __('barcode.view_product') }}
                                </a>
                                <button type="button" onclick="addToCart()" data-no-loading
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                                    <i class="ph-bold ph-shopping-cart"></i>
                                    {{ __('barcode.add_to_cart') }}
                                </button>
                                <button type="button" onclick="clearResult()" data-no-loading
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
                                    <i class="ph-bold ph-barcode"></i>
                                    {{ __('barcode.scan_another') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Not Found Message --}}
            <div id="not-found-message" class="hidden">
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-red-200 bg-red-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="ph-fill ph-warning text-red-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-700">{{ __('barcode.product_not_found') }}</h4>
                            <p id="not-found-code" class="text-red-600 font-mono"></p>
                        </div>
                        <button type="button" onclick="clearResult()" data-no-loading
                            class="ml-auto px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition">
                            {{ __('barcode.scan_another') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Recent Scans --}}
        <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-clock-counter-clockwise text-purple-500"></i>
                        {{ __('barcode.recent_scans') }}
                    </h3>
                    <button type="button" onclick="clearHistory()" data-no-loading
                        class="text-xs text-gray-400 hover:text-red-500 transition">
                        {{ __('barcode.clear_history') }}
                    </button>
                </div>

                <div id="recent-scans" class="space-y-2">
                    <div id="no-recent-scans" class="text-center py-8 text-gray-400">
                        <i class="ph ph-barcode text-4xl mb-2"></i>
                        <p class="text-sm">{{ __('barcode.no_recent_scans') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- QuaggaJS for barcode scanning --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let cameraActive = false;
        let currentProduct = null;
        let isProcessingLookup = false;
        let recentScans = JSON.parse(localStorage.getItem('recentScans') || '[]');

        // Sound Effects
        const scanSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
        const errorSound = new Audio(
            'https://assets.mixkit.co/active_storage/sfx/123/123-preview.mp3'); // A subtle error buzz
        scanSound.volume = 0.5;
        errorSound.volume = 0.3;

        document.addEventListener('DOMContentLoaded', function() {
            renderRecentScans();
            // Focus on input
            document.getElementById('barcode-input').focus();
        });

        function handleBarcodeInput(event) {
            if (event.key === 'Enter') {
                lookupBarcode();
            }
        }

        async function lookupBarcode() {
            if (isProcessingLookup) return;
            isProcessingLookup = true;

            const input = document.getElementById('barcode-input');
            const code = input.value.trim();

            if (!code) return;

            try {
                const response = await fetch(`/barcode/lookup?code=${encodeURIComponent(code)}`);
                const data = await response.json();

                if (data.success) {
                    showProduct(data.product);
                    addToHistory(data.product);
                    playBeep(true);

                    // Auto-stop camera after successful scan
                    if (cameraActive) {
                        stopCamera();
                    }
                } else {
                    showNotFound(code);
                    playBeep(false);
                }
            } catch (error) {
                console.error('Lookup error:', error);
                showNotFound(code);
            }

            input.value = '';
            input.focus();

            // Lock for 1 second to prevent double scans from camera
            setTimeout(() => {
                isProcessingLookup = false;
            }, 1000);
        }

        function showProduct(product) {
            currentProduct = product;

            document.getElementById('product-name').textContent = product.name;
            document.getElementById('product-name-th').textContent = product.name_th || '';
            document.getElementById('product-sku').textContent = product.sku || '-';
            document.getElementById('product-price').textContent = '฿' + parseFloat(product.unit_price || 0).toLocaleString(
                'th-TH', {
                    minimumFractionDigits: 2
                });
            document.getElementById('product-member-price').textContent = product.member_price ? '฿' + parseFloat(product
                .member_price).toLocaleString('th-TH', {
                minimumFractionDigits: 2
            }) : '-';
            document.getElementById('product-stock').textContent = parseFloat(product.stock_qty || 0).toLocaleString(
                'th-TH');
            document.getElementById('product-link').href = product.url;

            // Image
            const img = document.getElementById('product-image');
            const placeholder = document.getElementById('product-placeholder-icon');
            if (product.image_url) {
                img.src = product.image_url;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            document.getElementById('product-result').classList.remove('hidden');
            document.getElementById('not-found-message').classList.add('hidden');
        }

        function showNotFound(code) {
            document.getElementById('not-found-code').textContent = `"${code}"`;
            document.getElementById('not-found-message').classList.remove('hidden');
            document.getElementById('product-result').classList.add('hidden');
        }

        function clearResult() {
            document.getElementById('product-result').classList.add('hidden');
            document.getElementById('not-found-message').classList.add('hidden');
            document.getElementById('barcode-input').focus();
            currentProduct = null;
        }

        function addToHistory(product) {
            // Remove if already exists
            recentScans = recentScans.filter(p => p.id !== product.id);
            // Add to front
            recentScans.unshift(product);
            // Keep only last 10
            recentScans = recentScans.slice(0, 10);
            // Save
            localStorage.setItem('recentScans', JSON.stringify(recentScans));
            renderRecentScans();
        }

        function renderRecentScans() {
            const container = document.getElementById('recent-scans');

            if (recentScans.length === 0) {
                container.innerHTML = `
                    <div id="no-recent-scans" class="text-center py-8 text-gray-400">
                        <i class="ph ph-barcode text-4xl mb-2"></i>
                        <p class="text-sm">{{ __('barcode.no_recent_scans') }}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = recentScans.map(p => {
                const imageHtml = p.image_url ?
                    `<img src="${p.image_url}" alt="${p.name}" class="w-full h-full object-cover rounded-lg">` :
                    `<i class="ph-fill ph-pill text-orange-600"></i>`;

                return `
                <a href="${p.url}" class="block p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            ${imageHtml}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">${p.name}</p>
                            <p class="text-xs text-gray-500 font-mono">${p.sku || p.barcode || ''}</p>
                        </div>
                        <span class="text-sm font-bold text-green-600">฿${parseFloat(p.unit_price || 0).toFixed(0)}</span>
                    </div>
                </a>
            `;
            }).join('');
        }

        function clearHistory() {
            recentScans = [];
            localStorage.removeItem('recentScans');
            renderRecentScans();
        }

        function toggleCamera() {
            if (cameraActive) {
                stopCamera();
            } else {
                startCamera();
            }
        }

        function startCamera() {
            const container = document.getElementById('camera-container');
            const btn = document.getElementById('camera-toggle-btn');
            const icon = document.getElementById('camera-icon');
            const text = document.getElementById('camera-text');

            container.classList.remove('hidden');
            btn.classList.remove('bg-green-500', 'hover:bg-green-600');
            btn.classList.add('bg-red-500', 'hover:bg-red-600');
            icon.classList.remove('ph-camera');
            icon.classList.add('ph-camera-slash');
            text.textContent = '{{ __('barcode.stop_camera') }}';

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#camera-viewport'),
                    constraints: {
                        facingMode: "environment",
                        width: {
                            min: 640,
                            ideal: 1280
                        },
                        height: {
                            min: 480,
                            ideal: 720
                        }
                    },
                },
                locator: {
                    patchSize: "medium",
                    halfSample: true
                },
                numOfWorkers: navigator.hardwareConcurrency || 2,
                decoder: {
                    readers: ["ean_reader", "ean_8_reader", "code_128_reader", "code_39_reader", "upc_reader",
                        "upc_e_reader"
                    ]
                }
            }, function(err) {
                if (err) {
                    console.error(err);
                    alert('{{ __('barcode.camera_permission') }}');
                    stopCamera();
                    return;
                }
                Quagga.start();
                cameraActive = true;
            });

            Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                document.getElementById('barcode-input').value = code;
                lookupBarcode();
            });
        }

        function stopCamera() {
            const container = document.getElementById('camera-container');
            const btn = document.getElementById('camera-toggle-btn');
            const icon = document.getElementById('camera-icon');
            const text = document.getElementById('camera-text');

            Quagga.stop();
            container.classList.add('hidden');
            btn.classList.remove('bg-red-500', 'hover:bg-red-600');
            btn.classList.add('bg-green-500', 'hover:bg-green-600');
            icon.classList.remove('ph-camera-slash');
            icon.classList.add('ph-camera');
            text.textContent = '{{ __('barcode.start_camera') }}';
            cameraActive = false;
        }

        async function addToCart() {
            if (!currentProduct) return;

            // Store in session storage for POS to pick up
            let cart = JSON.parse(sessionStorage.getItem('posCart') || '[]');
            const existingIndex = cart.findIndex(item => item.id === currentProduct.id);

            if (existingIndex >= 0) {
                cart[existingIndex].quantity += 1;
            } else {
                cart.push({
                    id: currentProduct.id,
                    name: currentProduct.name,
                    sku: currentProduct.sku,
                    unit_price: parseFloat(currentProduct.unit_price),
                    quantity: 1
                });
            }

            sessionStorage.setItem('posCart', JSON.stringify(cart));

            // Show toast
            if (typeof showToast === 'function') {
                showToast('{{ __('barcode.added_to_cart') }}: ' + currentProduct.name, 'success');
            }

            clearResult();
        }

        function playBeep(success) {
            if (success) {
                scanSound.currentTime = 0;
                scanSound.play().catch(e => {});
            } else {
                errorSound.currentTime = 0;
                errorSound.play().catch(e => {});
            }
        }
    </script>
@endpush
