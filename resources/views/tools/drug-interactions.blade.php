@extends('layouts.app')

@section('title', __('drug_interactions.title'))

@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('drug_interactions.page_subtitle') }}
        </p>
        <span>{{ __('drug_interactions.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Search Card --}}
        <div
            class="bg-white/80 backdrop-blur-md rounded-[2.5rem] p-10 border border-white shadow-2xl relative overflow-hidden">
            {{-- Decorative background --}}
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-ios-blue/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-red-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-8">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl font-black text-gray-900">{{ __('drug_interactions.checker_title') }}</h2>
                    <p class="text-gray-500 max-w-md mx-auto">{{ __('drug_interactions.checker_desc') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    {{-- Drug A --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest px-4">
                            {{ __('drug_interactions.drug_a') }}
                        </label>
                        <div class="relative group">
                            <div
                                class="absolute left-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center transition-colors group-focus-within:bg-ios-blue/10">
                                <i class="ph-bold ph-pill text-ios-blue"></i>
                            </div>
                            <input type="text" id="drug-a" placeholder="{{ __('drug_interactions.enter_drug_name') }}"
                                class="w-full bg-gray-50/50 border-none rounded-2xl py-5 pl-18 pr-6 text-lg font-bold placeholder:text-gray-300 focus:ring-2 focus:ring-ios-blue/20 transition-all outline-none"
                                autocomplete="off">
                            <div id="suggestions-a" class="suggestions-dropdown hidden"></div>
                        </div>
                    </div>

                    {{-- Visual Link --}}
                    <div class="hidden md:flex justify-center -mb-8">
                        <div
                            class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-sm">
                            <i class="ph-bold ph-swap text-gray-400"></i>
                        </div>
                    </div>

                    {{-- Drug B --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest px-4">
                            {{ __('drug_interactions.drug_b') }}
                        </label>
                        <div class="relative group">
                            <div
                                class="absolute left-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center transition-colors group-focus-within:bg-orange-500/10">
                                <i class="ph-bold ph-pill text-orange-500"></i>
                            </div>
                            <input type="text" id="drug-b" placeholder="{{ __('drug_interactions.enter_drug_name') }}"
                                class="w-full bg-gray-50/50 border-none rounded-2xl py-5 pl-18 pr-6 text-lg font-bold placeholder:text-gray-300 focus:ring-2 focus:ring-ios-blue/20 transition-all outline-none"
                                autocomplete="off">
                            <div id="suggestions-b" class="suggestions-dropdown hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button onclick="checkInteractions()" data-no-loading
                        class="w-full bg-ios-blue hover:brightness-110 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 transition active-scale flex items-center justify-center gap-3">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        <span>{{ __('drug_interactions.check_now') }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Results Container --}}
        <div id="results-container" class="hidden animate-fade-in">
            <div id="interaction-result" class="space-y-6">
                {{-- Rendered via JS --}}
            </div>
        </div>

        {{-- Empty State / Instructions --}}
        <div id="empty-state" class="text-center py-12 space-y-4">
            <div class="w-20 h-20 bg-gray-100 rounded-[2.5rem] flex items-center justify-center mx-auto">
                <i class="ph ph-shield-check text-4xl text-gray-300"></i>
            </div>
            <p class="text-gray-400 font-medium">{{ __('drug_interactions.instructions') }}</p>
        </div>
    </div>

    <style>
        .pl-18 {
            padding-left: 4.5rem;
        }

        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 1rem;
            margin-top: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 50;
            max-height: 200px;
            overflow-y: auto;
        }

        .suggestion-item {
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            font-weight: 600;
            color: #4b5563;
            transition: all 0.2s;
        }

        .suggestion-item:hover {
            background: #f3f4f6;
            color: var(--ios-blue);
        }

        .interaction-card {
            background: white;
            border-radius: 2rem;
            padding: 2.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
@endsection

@push('scripts')
    <script>
        const drugInputA = document.getElementById('drug-a');
        const drugInputB = document.getElementById('drug-b');
        const suggA = document.getElementById('suggestions-a');
        const suggB = document.getElementById('suggestions-b');

        // Autocomplete Logic
        function setupAutocomplete(input, dropdown) {
            let timeout;
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                const val = input.value.trim();
                if (val.length < 2) {
                    dropdown.classList.add('hidden');
                    return;
                }

                timeout = setTimeout(async () => {
                    const res = await fetch(`{{ route('drug-interactions.suggest') }}?term=${val}`);
                    const suggestions = await res.json();

                    if (suggestions.length > 0) {
                        dropdown.innerHTML = suggestions.map(s => `
                        <div class="suggestion-item" onclick="selectSuggestion('${input.id}', '${s}')">${s}</div>
                    `).join('');
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.classList.add('hidden');
                    }
                }, 300);
            });

            // Close on blur
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        function selectSuggestion(inputId, value) {
            document.getElementById(inputId).value = value;
            document.getElementById(`suggestions-${inputId.split('-')[1]}`).classList.add('hidden');
        }

        setupAutocomplete(drugInputA, suggA);
        setupAutocomplete(drugInputB, suggB);

        async function checkInteractions() {
            const drugA = drugInputA.value.trim();
            const drugB = drugInputB.value.trim();

            if (!drugA || !drugB) {
                toast('{{ __('drug_interactions.error_missing') }}', 'error');
                return;
            }

            const res = await fetch(`{{ route('drug-interactions.search') }}?drug_a=${drugA}&drug_b=${drugB}`);
            const data = await res.json();

            const resultsContainer = document.getElementById('results-container');
            const resultsContent = document.getElementById('interaction-result');
            const emptyState = document.getElementById('empty-state');

            emptyState.classList.add('hidden');
            resultsContainer.classList.remove('hidden');

            if (data.length === 0) {
                resultsContent.innerHTML = `
                <div class="interaction-card text-center space-y-4">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto">
                        <i class="ph-bold ph-shield-check text-2xl text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('drug_interactions.no_interaction') }}</h3>
                        <p class="text-gray-500">{{ __('drug_interactions.no_interaction_desc') }}</p>
                    </div>
                </div>
            `;
                return;
            }

            resultsContent.innerHTML = data.map(item => {
                let severityClass = '';
                let severityIcon = '';
                let severityText = '';

                switch (item.severity) {
                    case 'minor':
                        severityClass = 'bg-blue-50 border-blue-100 text-blue-700';
                        severityIcon = 'ph-info';
                        severityText = '{{ __('drug_interactions.minor') }}';
                        break;
                    case 'moderate':
                        severityClass = 'bg-orange-50 border-orange-100 text-orange-700';
                        severityIcon = 'ph-warning';
                        severityText = '{{ __('drug_interactions.moderate') }}';
                        break;
                    case 'major':
                        severityClass = 'bg-red-50 border-red-100 text-red-700';
                        severityIcon = 'ph-warning-octagon';
                        severityText = '{{ __('drug_interactions.major') }}';
                        break;
                    case 'contraindicated':
                        severityClass = 'bg-gray-900 border-gray-800 text-white';
                        severityIcon = 'ph-prohibit';
                        severityText = '{{ __('drug_interactions.contraindicated') }}';
                        break;
                }

                return `
                <div class="interaction-card animate-fade-in">
                    <div class="flex flex-col md:flex-row gap-8">
                        {{-- Left Side: Drugs & Severity --}}
                        <div class="md:w-1/3 space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter ${severityClass} flex items-center gap-1">
                                    <i class="ph-bold ${severityIcon}"></i>
                                    ${severityText}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <div class="text-lg font-black text-gray-900">${item.drug_a_name}</div>
                                <div class="flex justify-center w-full py-1">
                                    <i class="ph ph-link text-gray-300"></i>
                                </div>
                                <div class="text-lg font-black text-gray-900">${item.drug_b_name}</div>
                            </div>
                        </div>

                        {{-- Right Side: Details --}}
                        <div class="md:w-2/3 space-y-6">
                            <div class="space-y-2">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('drug_interactions.description') }}</h4>
                                <p class="text-gray-700 leading-relaxed font-medium">${item.description}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('drug_interactions.mechanism') }}</h4>
                                    <p class="text-sm text-gray-600">${item.mechanism || '-'}</p>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('drug_interactions.management') }}</h4>
                                    <p class="text-sm text-gray-600">${item.management || '-'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        }
    </script>
@endpush
