@extends('layouts.app')

@section('title', __('general.regulations'))
@section('page-title', __('general.regulations'))

@section('content')
    <div class="space-y-6 pb-10">
        {{-- Header Illustration --}}
        <div class="card-ios p-8 bg-gradient-to-br from-indigo-500 to-purple-600 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                    <i class="ph-fill ph-scales text-white text-3xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-white mb-2">{{ __('general.regulations') }}</h1>
                    <p class="text-white/80 max-w-2xl">
                        รวมข้อกำหนด กฎหมาย และมาตรฐานการปฏิบัติงานทางเภสัชกรรม (Pharmacy Compliance)
                        เพื่อความถูกต้องและความปลอดภัยสูงสุดแก่ผู้รับบริการ
                    </p>
                </div>
            </div>
            {{-- Decorative icons --}}
            <i class="ph ph-shield-check text-white/5 text-[120px] absolute -right-4 -bottom-4"></i>
        </div>

        {{-- Categories Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Core Laws --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-gavel text-indigo-500"></i>
                    กฎหมายหลัก (Core Drug Acts)
                </h3>

                <div class="space-y-4">
                    <div
                        class="p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-indigo-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                <i class="ph ph-book-open text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">พระราชบัญญัติยา (พ.ศ. 2510 และฉบับแก้ไข)</h4>
                                <p class="text-sm text-gray-500">ควบคุมการนำเข้า ผลิต และจำหน่ายยา รวมถึงการแบ่งประเภทของยา
                                    (ยาอันตราย, ยาควบคุมพิเศษ, ยาสามัญประจำบ้าน)</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-indigo-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                                <i class="ph ph-shield-warning text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">พ.ร.บ. วัตถุที่ออกฤทธิ์ต่อจิตและประสาท</h4>
                                <p class="text-sm text-gray-500">ข้อกำหนดการจ่ายยาในกลุ่มวัตถุออกฤทธิ์ฯ ประเภท 3 และ 4
                                    และการจัดทำรายงานขย.9, ขย.10 ส่งสำนักงาน อย.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-indigo-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                                <i class="ph ph-warning-diamond text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">พ.ร.บ. ยาเสพติดให้โทษ</h4>
                                <p class="text-sm text-gray-500">การควบคุมและจดบันทึกการจ่ายยาสูตรผสมที่มีส่วนประกอบของกัญชา
                                    หรือวัตถุเสพติดประเภทต่างๆ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Professional Standards --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-scroll text-blue-500"></i>
                    มาตรฐานวิชาชีพ (Professional Standards)
                </h3>

                <div class="space-y-4">
                    <div
                        class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 group hover:border-blue-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                <i class="ph ph-certificate text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">GPP (Good Pharmacy Practice)</h4>
                                <p class="text-sm text-gray-500">มาตรฐานวิธีปฏิบัติที่ดีทางเภสัชกรรมชุมชน การจัดวางสถานที่
                                    ระบบการจัดเก็บยา และหน้าที่ของเภสัชกร</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100 group hover:border-emerald-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                <i class="ph ph-users-three text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">จรรยาบรรณวิชาชีพเภสัชกรรม</h4>
                                <p class="text-sm text-gray-500">ข้อบังคับสภาเภสัชกรรมว่าด้วยจรรยาบรรณวิชาชีพ การให้บริการ
                                    และการรักษาความลับของผู้ป่วย</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 group hover:border-indigo-300 transition-all cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                <i class="ph ph-file-text text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA)</h4>
                                <p class="text-sm text-gray-500">แนวทางการจัดการข้อมูลสุขภาพ ประวัติการแพ้ยา
                                    และข้อมูลติดต่อของลูกค้าในระบบดิจิทัล</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Checklist Card --}}
        <div class="card-ios p-6 border-t-4 border-ios-blue">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="ph-fill ph-checks text-ios-blue"></i>
                สิ่งที่ต้องปฏิบัติในการตรวจรับรองร้าน (Compliance Checklist)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 text-sm uppercase tracking-wider">เอกสารประจำสถานประกอบการ</h4>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>ใบอนุญาตขายยา (ขย.5) ตัวจริง</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>รูปถ่ายเภสัชกรผู้มีหน้าที่ปฏิบัติการ</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>ป้ายแสดงสถานะการปฏิบัติหน้าที่</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 text-sm uppercase tracking-wider">ระบบการจัดการยา</h4>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>การแยกโวนยาหมดอายุชัดเจน</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>ตู้เก็บวัตถุออกฤทธิ์ฯ แบบมีกุญแจ</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>ตู้เย็นเก็บยาที่ควบคุมอุณหภูมิ 2-8°C</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 text-sm uppercase tracking-wider">การบันทึกรายงาน</h4>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>สมุดบัญชีรับ-จ่ายยาควบคุมพิเศษ</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>รายงานสรุปรายเดือน (บจ.8/9)</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="ph-bold ph-check-circle text-emerald-500"></i>
                        <span>บันทึกการส่งคืนหรือทำลายยา</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resource Links --}}
        <div class="flex items-center justify-center gap-4 py-4">
            <a href="https://www.fda.moph.go.th" target="_blank"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 transition-all font-medium border border-indigo-100">
                <i class="ph ph-arrow-square-out"></i>
                สำนักงานคณะกรรมการอาหารและยา (อย.)
            </a>
            <a href="https://www.pharmacycouncil.org" target="_blank"
                class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-all font-medium border border-blue-100">
                <i class="ph ph-arrow-square-out"></i>
                สภาเภสัชกรรม
            </a>
        </div>
    </div>
@endsection
