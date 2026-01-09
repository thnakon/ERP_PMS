@extends('layouts.app')

@section('title', __('products.edit_product') . ': ' . $product->name)

@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('products.show', $product) }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            {{ __('back_to_products') }}
        </a>
        <span>{{ __('products.edit_product') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('products.show', $product) }}"
            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition active-scale">
            {{ __('general.cancel') }}
        </a>
        <button type="button" onclick="submitEditForm()"
            class="px-7 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-floppy-disk"></i>
            {{ __('general.save') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="w-full">
        <form id="edit-product-form" action="{{ route('products.update', $product) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Left Column: Image & Basic Info --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <div class="flex flex-col items-center">
                            <div id="image-upload-area"
                                class="relative w-32 h-32 bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-ios-blue hover:from-blue-50/50 transition-all group overflow-hidden">
                                <input type="file" name="image" id="product-image-input" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div id="image-preview"
                                    class="{{ $product->image_path ? '' : 'hidden' }} absolute inset-0 w-full h-full rounded-2xl overflow-hidden">
                                    <img id="image-preview-img"
                                        src="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}"
                                        alt="Preview" class="w-full h-full object-cover">
                                </div>

                                <div id="image-placeholder"
                                    class="{{ $product->image_path ? 'hidden' : '' }} flex flex-col items-center text-gray-400 group-hover:text-ios-blue transition">
                                    <i class="ph ph-camera text-3xl mb-1"></i>
                                    <span class="text-xs font-medium">{{ __('add_photo') }}</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-3 text-center">
                                {{ __('image_format') }} • {{ __('image_size') }}
                            </p>

                            <div class="mt-4 flex items-center gap-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ $product->is_active ? 'checked' : '' }}
                                        class="form-checkbox h-4 w-4 text-ios-blue rounded border-gray-300">
                                    <span class="text-xs font-bold text-gray-600">{{ __('products.is_active') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                            {{ __('products.system_meta') }}</h3>
                        <div class="space-y-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('products.sku') }} <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="sku" value="{{ $product->sku }}" class="form-input"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('products.barcode') }}</label>
                                <input type="text" name="barcode" value="{{ $product->barcode }}" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('products.fda_registration_no') }}</label>
                                <input type="text" name="fda_registration_no"
                                    value="{{ $product->fda_registration_no }}" class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                            {{ __('products.shelf_location') }}</h3>
                        <div class="form-group">
                            <label class="form-label">{{ __('products.shelf_location') }}</label>
                            <input type="text" name="location" value="{{ $product->location }}" class="form-input"
                                placeholder="A1-01">
                        </div>
                    </div>
                </div>

                {{-- Right Column: Detailed Info --}}
                <div class="md:col-span-2 space-y-6">
                    {{-- Basic Info --}}
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <div class="form-section-header mb-6">
                            <i class="ph ph-identification-card text-ios-blue"></i>
                            <span class="text-lg font-semibold">{{ __('general.basic_info') }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group md:col-span-2">
                                <label class="form-label font-bold text-blue-600">{{ __('products.name') }} (English) <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ $product->name }}"
                                    class="form-input text-lg font-medium" required>
                            </div>
                            <div class="form-group md:col-span-2">
                                <label class="form-label font-bold text-red-600">{{ __('products.name_th') }}
                                    (ภาษาไทย)</label>
                                <input type="text" name="name_th" value="{{ $product->name_th }}"
                                    class="form-input text-lg font-medium">
                            </div>
                            <div class="form-group md:col-span-2">
                                <label class="form-label">{{ __('products.generic_name') }}</label>
                                <input type="text" name="generic_name" value="{{ $product->generic_name }}"
                                    class="form-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-50">
                            <div class="form-group">
                                <label class="form-label">{{ __('products.category') }}</label>
                                <select name="category_id" class="form-input">
                                    <option value="">{{ __('products.select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->localized_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('products.drug_class') }}</label>
                                <select name="drug_class" class="form-input">
                                    <option value="">{{ __('products.drug_class') }}</option>
                                    @foreach (['class_general', 'class_dangerous', 'class_special', 'class_supplement', 'class_medical_supply'] as $key)
                                        <option value="{{ __('products.' . $key, [], 'th') }}"
                                            {{ $product->drug_class == __('products.' . $key, [], 'th') ? 'selected' : '' }}>
                                            {{ __('products.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group md:col-span-2">
                                <label class="form-label">{{ __('products.manufacturer') }}</label>
                                <input type="text" name="manufacturer" value="{{ $product->manufacturer }}"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    {{-- Pricing & Inventory --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-orange-100/50">
                            <div class="form-section-header mb-4">
                                <i class="ph ph-currency-circle-dollar text-orange-500"></i>
                                <span class="font-bold text-gray-700">{{ __('products.pricing_inventory') }}</span>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.cost') }}</label>
                                        <input type="number" step="0.01" name="cost_price"
                                            value="{{ $product->cost_price }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="form-label text-xs font-bold text-ios-blue">{{ __('products.price') }}
                                            *</label>
                                        <input type="number" step="0.01" name="unit_price"
                                            value="{{ $product->unit_price }}" class="form-input font-bold text-ios-blue"
                                            required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.member_price') }}</label>
                                        <input type="number" step="0.01" name="member_price"
                                            value="{{ $product->member_price }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.base_unit') }}</label>
                                        <input type="text" name="base_unit" value="{{ $product->base_unit }}"
                                            class="form-input">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-toggle">
                                        <input type="checkbox" name="vat_applicable" value="1"
                                            {{ $product->vat_applicable ? 'checked' : '' }} class="form-toggle-input">
                                        <span class="form-toggle-label text-xs">{{ __('products.vat_applicable') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-green-100/50">
                            <div class="form-section-header mb-4">
                                <i class="ph ph-package text-green-500"></i>
                                <span class="font-bold text-gray-700">{{ __('products.stock') }}</span>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.stock') }}</label>
                                        <input type="number" name="stock_qty" value="{{ $product->stock_qty }}"
                                            class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.min_stock') }}</label>
                                        <input type="number" name="min_stock" value="{{ $product->min_stock }}"
                                            class="form-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.reorder_point') }}</label>
                                        <input type="number" name="reorder_point" value="{{ $product->reorder_point }}"
                                            class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-xs">{{ __('products.max_stock') }}</label>
                                        <input type="number" name="max_stock" value="{{ $product->max_stock }}"
                                            class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description & Instructions --}}
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <div class="form-section-header mb-6">
                            <i class="ph ph-article text-ios-blue"></i>
                            <span class="text-lg font-semibold">{{ __('products.description') }} &
                                {{ __('products.instructions') }}</span>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label font-bold text-blue-600">{{ __('products.description') }}
                                        (EN)</label>
                                    <textarea name="description" class="form-input min-h-[100px]">{{ $product->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-bold text-red-600">{{ __('products.description') }}
                                        (TH)</label>
                                    <textarea name="description_th" class="form-input min-h-[100px]">{{ $product->description_th }}</textarea>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label font-bold text-blue-600">{{ __('products.instructions') }}
                                        (EN)</label>
                                    <textarea name="default_instructions" class="form-input min-h-[100px]">{{ $product->default_instructions }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-bold text-red-600">{{ __('products.instructions') }}
                                        (TH)</label>
                                    <textarea name="default_instructions_th" class="form-input min-h-[100px]">{{ $product->default_instructions_th }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Clinical Info --}}
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <div class="form-section-header mb-6">
                            <i class="ph ph-first-aid-kit text-red-500"></i>
                            <span class="text-lg font-semibold">{{ __('products.clinical_regulatory') }}</span>
                        </div>
                        <div class="space-y-6">
                            <label class="form-toggle">
                                <input type="checkbox" name="requires_prescription" value="1"
                                    {{ $product->requires_prescription ? 'checked' : '' }} class="form-toggle-input">
                                <span class="form-toggle-label">{{ __('products.requires_prescription') }}</span>
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label font-bold text-blue-600">{{ __('products.precautions') }}
                                        (EN)</label>
                                    <textarea name="precautions" class="form-input min-h-[80px]">{{ $product->precautions }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-bold text-red-600">{{ __('products.precautions') }}
                                        (TH)</label>
                                    <textarea name="precautions_th" class="form-input min-h-[80px]">{{ $product->precautions_th }}</textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label font-bold text-blue-600">{{ __('products.side_effects') }}
                                        (EN)</label>
                                    <textarea name="side_effects" class="form-input min-h-[80px]">{{ $product->side_effects }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-bold text-red-600">{{ __('products.side_effects') }}
                                        (TH)</label>
                                    <textarea name="side_effects_th" class="form-input min-h-[80px]">{{ $product->side_effects_th }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('product-image-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('image-preview-img').src = event.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('image-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function submitEditForm() {
            document.getElementById('edit-product-form').submit();
        }
    </script>
@endpush
