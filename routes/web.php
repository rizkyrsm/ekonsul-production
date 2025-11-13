<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ControllerBeranda;
use App\Livewire\Dashboard\DashController;
use App\Livewire\Dashboard\UserCreate;
use App\Livewire\Dashboard\ProfileDetail;
use App\Livewire\Dashboard\LayananCreate;
use App\Livewire\Dashboard\DiskonCreate;
use App\Livewire\Dashboard\DashKeranjang;
use App\Livewire\Dashboard\DashKonseling;
use App\Livewire\Dashboard\DashOrder;
use App\Livewire\Dashboard\ReportOrder;
use App\Http\Controllers\ReportOrderExportController;
use App\Http\Controllers\ExtendedMessageController;
use App\Http\Controllers\UserMessageController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| ROUTE UTAMA (PUBLIC)
|--------------------------------------------------------------------------
*/

// ✅ Route root utama — tidak butuh login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('beranda');
})->name('home');

// ✅ Halaman beranda publik
Route::get('/beranda', [ControllerBeranda::class, 'listlayanan'])->name('beranda');

// ✅ Buat symbolic link storage (hanya admin tahu)
Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'Storage linked!';
});

// ✅ Simpan FCM Token (login wajib)
Route::post('/save-fcm-token', function (Request $request) {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json(['success' => true, 'token' => $request->fcm_token]);
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| ROUTE YANG BUTUH LOGIN (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // Report Order (Livewire)
    Route::get('/dashboard/report-order', ReportOrder::class)
        ->name('dashboard.report-order');

    // Export Excel Report Order
    Route::get('/dashboard/report-order/export', [ReportOrderExportController::class, 'export'])
        ->name('dashboard.report-order.export');

    // Popup rangkuman
    Route::get('/konseling/rangkuman/{id_order}', [DashKonseling::class, 'getRangkuman'])
        ->name('konseling.getRangkuman');

    Route::post('/konseling/rangkuman/{id_order}', [DashKonseling::class, 'simpanRangkuman'])
        ->name('konseling.simpanRangkuman');

    // All message user
    Route::get('/allChat/{user_id}', [UserMessageController::class, 'index'])->name('allChat');

    Route::get('/dashboard/toggle-status', [DashController::class, 'toggleStatus'])->name('dashboard.toggle-status');
    Route::post('/auto-finish-order', [ExtendedMessageController::class, 'autoFinishOrder']);
    Route::get('/notif-redirect/{notifId}', [DashController::class, 'redirectNotif'])->name('notif.redirect');

    // User management
    Route::get('/dashboard/users/create', UserCreate::class)
        ->name('dashboard.users.create')
        ->middleware('role:ADMIN,CABANG');

    // Layanan management
    Route::get('/dashboard/layanan/create', LayananCreate::class)
        ->name('dashboard.layanan.create')
        ->middleware('role:ADMIN');

    // Diskon management
    Route::get('/dashboard/diskon/create', DiskonCreate::class)
        ->name('dashboard.diskon.create')
        ->middleware('role:ADMIN');

    // Keranjang (hanya USER)
    Route::get('/keranjang/{id?}', DashKeranjang::class)
        ->name('dashboard.keranjang')
        ->middleware('role:USER');

    // Order (semua role)
    Route::get('/orders', DashOrder::class)
        ->name('orders')
        ->middleware('role:USER,CABANG,ADMIN');

    // Konseling
    Route::get('/konseling', DashKonseling::class)->name('konseling');
    Route::patch('/konseling/{id}/update-status', [DashKonseling::class, 'updateStatus'])->name('konseling.updateStatus');

    // Custom chat iframe
    Route::get('/custom-chat/{from_id}/{to_id}/{id_order}', function ($from_id, $to_id, $id_order) {
        return view('custom-chat', compact('from_id', 'to_id', 'id_order'));
    });

    // JSON Profil detail
    Route::get('/profile-detail-json/{id}', function ($id) {
        $detail = \App\Models\DetailUser::where('id_user', $id)->first();
        if (!$detail) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($detail);
    });

    // Cek kelengkapan profil
    Route::get('/check-profile/{id}', function ($id) {
        $data = \App\Models\DetailUser::where('id_user', $id)->first();
        $requiredFields = [
            'nama', 'nik', 'tgl_lahir', 'tempat_lahir', 'alamat',
            'no_tlp', 'status_online', 'jenis_kelamin', 'status_pernikahan',
            'agama', 'pekerjaan'
        ];
        $isComplete = $data && collect($requiredFields)->every(fn($field) => !empty($data->$field));
        return response()->json(['complete' => $isComplete]);
    });

    // Pengaturan (Volt)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile-detail', ProfileDetail::class)->name('settings.profile-detail');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Chatify routes
    Route::post('/send', [ExtendedMessageController::class, 'send'])->name('send.message');
    Route::post('/chatify/fetchMessages', [ExtendedMessageController::class, 'fetch'])->name('fetch.messages');
});

/*
|--------------------------------------------------------------------------
| FALLBACK & AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});

require __DIR__ . '/auth.php';