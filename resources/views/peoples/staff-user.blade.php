<x-app-layout>
    <div class="sr-container">
        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / People / Staff | User</p>
                <h2 class="sr-page-title">Staff | User</h2>
            </div>
            <div class="sr-header-right">
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>เพิ่มพนักงานใหม่</span>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
