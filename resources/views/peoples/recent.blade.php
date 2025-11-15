<x-app-layout>
    <div class="sr-container">
        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / People / Recent</p>
                <h2 class="sr-page-title">Recent</h2>
            </div>
            <div class="sr-header-right">
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>ล่าสุด</span>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
