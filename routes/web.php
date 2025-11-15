<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PeoplesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

    // [!!! อัปเดตส่วนของ Reports !!!]
    // สมมติว่าคุณมี ReportsController ที่จะจัดการ view()
    // และเปลี่ยน 'index' (ของเดิม) ให้ชี้ไปที่ 'sales' เพื่อความชัดเจน

    // Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index'); // <-- ของเดิม
    
    Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/finance', [ReportsController::class, 'finance'])->name('reports.finance');
    Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');

        Route::get('/peoples/patients-customer', [PeoplesController::class, 'patientscustomer'])->name('peoples.patients-customer');
        Route::get('/peoples/staff-user', [PeoplesController::class, 'staffuser'])->name('peoples.staff-user');
        Route::get('/peoples/recent', [PeoplesController::class, 'recent'])->name('peoples.recent');
});

// Route สำหรับ Live Search (ที่ JavaScript เรียก)
Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('search.live');

// Route สำหรับ Search ปกติ (กด Enter)
Route::get('/search', [SearchController::class, 'fullSearch'])->name('search.full');

// Route สำหรับ AI Search (กด Atom)
Route::get('/ai-search', [SearchController::class, 'aiSearch'])->name('search.ai');

require __DIR__.'/auth.php';