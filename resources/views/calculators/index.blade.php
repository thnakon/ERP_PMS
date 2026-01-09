@extends('layouts.app')

@section('title', __('calculators.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('calculators.subtitle') }}
        </p>
        <span>{{ __('calculators.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Calculator Cards --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Pediatric --}}
            <div onclick="switchCalculator('pediatric')" id="card-pediatric"
                class="calculator-card bg-white rounded-3xl p-5 border-2 border-transparent shadow-sm hover:shadow-md transition-all cursor-pointer group active-scale">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-ios-blue/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-baby text-ios-blue text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('calculators.pediatric') }}</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ __('calculators.pediatric_desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- BMI --}}
            <div onclick="switchCalculator('bmi')" id="card-bmi"
                class="calculator-card bg-white rounded-3xl p-5 border-2 border-transparent shadow-sm hover:shadow-md transition-all cursor-pointer group active-scale">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-person text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('calculators.bmi') }}</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ __('calculators.bmi_desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- eGFR --}}
            <div onclick="switchCalculator('egfr')" id="card-egfr"
                class="calculator-card bg-white rounded-3xl p-5 border-2 border-transparent shadow-sm hover:shadow-md transition-all cursor-pointer group active-scale">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-shield-check text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('calculators.egfr') }}</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ __('calculators.egfr_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Calculator Area --}}
        <div class="lg:col-span-2">
            <div
                class="bg-white/80 backdrop-blur-md rounded-[2.5rem] p-8 border border-white shadow-xl min-h-[500px] relative overflow-hidden">
                {{-- Decorative background --}}
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-ios-blue/5 rounded-full blur-3xl pointer-events-none">
                </div>
                <div
                    class="absolute -left-20 -bottom-20 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl pointer-events-none">
                </div>

                {{-- Pediatric Form --}}
                <div id="calc-pediatric" class="calculator-view space-y-8">
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                        <i class="ph-fill ph-baby text-ios-blue"></i>
                        {{ __('calculators.pediatric') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.weight') }}</label>
                            <input type="number" id="ped-weight" step="0.1" class="calc-input" placeholder="0.0">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.dose_mg_kg') }}</label>
                            <input type="number" id="ped-dose" step="0.1" class="calc-input" placeholder="0.0">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.frequency') }}</label>
                            <select id="ped-freq" class="calc-input">
                                <option value="1">1 {{ __('units') }}/day (OD)</option>
                                <option value="2">2 {{ __('units') }}/day (bid)</option>
                                <option value="3">3 {{ __('units') }}/day (tid)</option>
                                <option value="4">4 {{ __('units') }}/day (qid)</option>
                            </select>
                        </div>
                    </div>

                    <button onclick="calculatePediatric()" data-no-loading class="calc-btn bg-ios-blue">
                        {{ __('calculators.calculate') }}
                    </button>

                    <div id="res-pediatric" class="hidden result-box bg-ios-blue/5 border-ios-blue/20">
                        <h4 class="text-ios-blue font-bold mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-check-circle"></i>
                            {{ __('calculators.result') }}
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">{{ __('calculators.total_daily') }}
                                </p>
                                <p class="text-2xl font-black text-gray-900"><span id="res-ped-daily">0</span> mg</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">{{ __('calculators.per_time') }}</p>
                                <p class="text-2xl font-black text-ios-blue"><span id="res-ped-time">0</span> mg</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BMI Form --}}
                <div id="calc-bmi" class="calculator-view hidden space-y-8">
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                        <i class="ph-fill ph-person text-green-600"></i>
                        {{ __('calculators.bmi') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.weight') }}</label>
                            <input type="number" id="bmi-weight" step="0.1" class="calc-input" placeholder="0.0">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.height') }}</label>
                            <input type="number" id="bmi-height" step="0.1" class="calc-input" placeholder="0.0">
                        </div>
                    </div>

                    <button onclick="calculateBMI()" data-no-loading class="calc-btn bg-green-600">
                        {{ __('calculators.calculate') }}
                    </button>

                    <div id="res-bmi" class="hidden result-box bg-green-50 border-green-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-green-700 font-bold mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-gauge"></i>
                                    BMI Score
                                </h4>
                                <p class="text-4xl font-black text-gray-900" id="res-bmi-val">0.0</p>
                            </div>
                            <div class="text-right">
                                <span id="res-bmi-badge"
                                    class="px-4 py-2 rounded-full font-bold text-xs uppercase tracking-widest"></span>
                                <p class="text-sm text-gray-600 font-bold mt-2" id="res-bmi-cat"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- eGFR Form --}}
                <div id="calc-egfr" class="calculator-view hidden space-y-8">
                    <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                        <i class="ph-fill ph-shield-check text-purple-600"></i>
                        {{ __('calculators.egfr') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.gender') }}</label>
                            <div class="flex gap-2">
                                <button onclick="setGender('male')" id="btn-male"
                                    class="flex-1 py-3 px-4 rounded-xl border-2 border-gray-100 font-bold text-gray-500 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-gender-male"></i> {{ __('calculators.male') }}
                                </button>
                                <button onclick="setGender('female')" id="btn-female"
                                    class="flex-1 py-3 px-4 rounded-xl border-2 border-gray-100 font-bold text-gray-500 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-gender-female"></i> {{ __('calculators.female') }}
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.age') }}</label>
                            <input type="number" id="egfr-age" class="calc-input" placeholder="0">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.weight') }}</label>
                            <input type="number" id="egfr-weight" step="0.1" class="calc-input" placeholder="0.0">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">{{ __('calculators.scr') }}</label>
                            <input type="number" id="egfr-scr" step="0.01" class="calc-input" placeholder="0.00">
                        </div>
                    </div>

                    <button onclick="calculateEGFR()" data-no-loading class="calc-btn bg-purple-600">
                        {{ __('calculators.calculate') }}
                    </button>

                    <div id="res-egfr" class="hidden result-box bg-purple-50 border-purple-200">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white border border-purple-100 flex items-center justify-center">
                                <i class="ph-bold ph-drop text-purple-600 text-3xl"></i>
                            </div>
                            <div>
                                <h4 class="text-purple-700 font-bold mb-1">Estimated CrCl</h4>
                                <div class="flex items-baseline gap-2">
                                    <p class="text-4xl font-black text-gray-900" id="res-egfr-val">0.0</p>
                                    <p class="text-gray-500 font-bold uppercase text-xs tracking-widest">mL/min</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .calc-input {
            width: 100%;
            background: #f9fafb;
            border: 2px solid #f3f4f6;
            border-radius: 1.25rem;
            padding: 1rem 1.25rem;
            font-weight: 700;
            color: #111827;
            transition: all 0.2s;
            outline: none;
        }

        .calc-input:focus {
            background: #fff;
            border-color: var(--ios-blue);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .calc-btn {
            width: 100%;
            padding: 1.25rem;
            border-radius: 1.5rem;
            color: #fff;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .calc-btn:active {
            transform: scale(0.98);
        }

        .calculator-card.active {
            border-color: var(--ios-blue);
            background: rgba(0, 122, 255, 0.02);
        }

        .result-box {
            padding: 2rem;
            border-radius: 2rem;
            border: 2px solid transparent;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gender-active {
            background: #f3f4f6;
            border-color: var(--ios-blue) !important;
            color: var(--ios-blue) !important;
        }

        .calc-input,
        .calc-btn {
            position: relative;
            z-index: 10;
        }
    </style>
@endsection

@push('scripts')
    <script>
        let currentCalc = 'pediatric';
        let selectedGender = 'male';

        function switchCalculator(type) {
            currentCalc = type;

            // Update cards
            document.querySelectorAll('.calculator-card').forEach(card => card.classList.remove('active'));
            document.getElementById('card-' + type).classList.add('active');

            // Update views
            document.querySelectorAll('.calculator-view').forEach(view => view.classList.add('hidden'));
            document.getElementById('calc-' + type).classList.remove('hidden');
        }

        function setGender(g) {
            selectedGender = g;
            document.getElementById('btn-male').classList.toggle('gender-active', g === 'male');
            document.getElementById('btn-female').classList.toggle('gender-active', g === 'female');
        }

        function calculatePediatric() {
            const weight = parseFloat(document.getElementById('ped-weight').value);
            const dose_mg_kg = parseFloat(document.getElementById('ped-dose').value);
            const frequency = parseInt(document.getElementById('ped-freq').value);

            if (isNaN(weight) || isNaN(dose_mg_kg)) return;

            const totalDailyDose = weight * dose_mg_kg;
            const dosePerTime = totalDailyDose / frequency;

            document.getElementById('res-ped-daily').textContent = totalDailyDose.toFixed(2);
            document.getElementById('res-ped-time').textContent = dosePerTime.toFixed(2);
            document.getElementById('res-pediatric').classList.remove('hidden');
        }

        function calculateBMI() {
            const weight = parseFloat(document.getElementById('bmi-weight').value);
            const height = parseFloat(document.getElementById('bmi-height').value);

            if (isNaN(height) || height <= 0 || isNaN(weight)) return;

            const heightM = height / 100;
            const bmi = weight / (heightM * heightM);

            const badge = document.getElementById('res-bmi-badge');
            const cat = document.getElementById('res-bmi-cat');

            document.getElementById('res-bmi-val').textContent = bmi.toFixed(2);

            let info = {};
            if (bmi < 18.5) {
                info = {
                    text: '{{ __('calculators.underweight') }}',
                    bg: 'bg-blue-100',
                    textCol: 'text-blue-600'
                };
            } else if (bmi < 25) {
                info = {
                    text: '{{ __('calculators.normal') }}',
                    bg: 'bg-green-100',
                    textCol: 'text-green-600'
                };
            } else if (bmi < 30) {
                info = {
                    text: '{{ __('calculators.overweight') }}',
                    bg: 'bg-orange-100',
                    textCol: 'text-orange-600'
                };
            } else {
                info = {
                    text: '{{ __('calculators.obese') }}',
                    bg: 'bg-red-100',
                    textCol: 'text-red-600'
                };
            }

            badge.className =
                `px-4 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest ${info.bg} ${info.textCol}`;
            badge.textContent = info.text;
            cat.textContent = info.text;

            document.getElementById('res-bmi').classList.remove('hidden');
        }

        function calculateEGFR() {
            const age = parseInt(document.getElementById('egfr-age').value);
            const weight = parseFloat(document.getElementById('egfr-weight').value);
            const scr = parseFloat(document.getElementById('egfr-scr').value);

            if (isNaN(age) || isNaN(weight) || isNaN(scr) || scr <= 0) return;

            let crcl = ((140 - age) * weight) / (72 * scr);
            if (selectedGender === 'female') {
                crcl *= 0.85;
            }

            document.getElementById('res-egfr-val').textContent = crcl.toFixed(2);
            document.getElementById('res-egfr').classList.remove('hidden');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            switchCalculator('pediatric');
            setGender('male');
        });
    </script>
@endpush
