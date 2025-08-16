<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ControllerBeranda;
use App\Livewire\Dashboard\DashController; // <- pastikan ini adalah controller biasa
use App\Livewire\Dashboard\UserCreate;
use App\Livewire\Dashboard\ProfileDetail;
use App\Livewire\Dashboard\LayananCreate;
use App\Livewire\Dashboard\DiskonCreate;
use App\Livewire\Dashboard\DashKeranjang;
use App\Livewire\Dashboard\DashKonseling;
use App\Livewire\Dashboard\DashOrder;
use App\Http\Controllers\ExtendedMessageController;
use App\Http\Controllers\UserMessageController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'Storage linked!';
});


Route::get('/beranda', [ControllerBeranda::class, 'listlayanan'])->name('beranda');

// ✅ FIXED: pastikan hanya 1 route untuk dashboard
Route::get('/dashboard', [DashController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // popup rangkuman
    Route::get('/konseling/rangkuman/{id_order}', [DashKonseling::class, 'getRangkuman'])
        ->name('konseling.getRangkuman');

    Route::post('/konseling/rangkuman/{id_order}', [DashKonseling::class, 'simpanRangkuman'])
        ->name('konseling.simpanRangkuman');


    // all message user
    Route::get('/allChat/{user_id}', [UserMessageController::class, 'index'])->name('allChat');

    Route::get('/dashboard/toggle-status', [DashController::class, 'toggleStatus'])->name('dashboard.toggle-status');
    Route::post('/auto-finish-order', [ExtendedMessageController::class, 'autoFinishOrder']);
    Route::get('/notif-redirect/{notifId}', [DashController::class, 'redirectNotif'])->name('notif.redirect');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('/dashboard/users/create', UserCreate::class)
        ->name('dashboard.users.create')
        ->middleware('role:ADMIN,CABANG'); // <- pastikan ini adalah controller biasa

    Route::get('/dashboard/layanan/create', LayananCreate::class)
        ->name('dashboard.layanan.create')
        ->middleware('role:ADMIN'); // <- pastikan ini adalah controller biasa

    Route::get('/dashboard/diskon/create', DiskonCreate::class)
        ->name('dashboard.diskon.create')
        ->middleware('role:ADMIN'); // <- pastikan ini adalah controller biasa

    Route::get('/keranjang/{id?}', DashKeranjang::class)
        ->name('dashboard.keranjang')
        ->middleware('role:USER'); // <- pastikan ini adalah controller biasa

    Route::get('/orders', DashOrder::class)
        ->name('orders')
        ->middleware('role:USER,CABANG,ADMIN'); // <- pastikan ini adalah controller biasa

    Route::get('/konseling', DashKonseling::class)
        ->name('konseling'); // <- pastikan ini adalah controller biasa
    Route::patch('/konseling/{id}/update-status', [DashKonseling::class, 'updateStatus'])->name('konseling.updateStatus');

    Route::get('/custom-chat/{from_id}/{to_id}/{id_order}', function ($from_id, $to_id, $id_order) {
        // Lakukan autentikasi manual atau validasi dari backend
        // Redirect ke Chatify jika perlu
        return view('custom-chat', compact('from_id', 'to_id', 'id_order'));
    });

    // untuk menampilkan profil di halaman konseling
    Route::get('/profile-detail-json/{id}', function ($id) {
        $detail = \App\Models\DetailUser::where('id_user', $id)->first();
        if (!$detail) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($detail);
    });

    // sebelum chat harus melengkapi profile
    Route::get('/check-profile/{id}', function ($id) {
        $data = \App\Models\DetailUser::where('id_user', $id)->first();
        $requiredFields = ['nama', 'nik', 'tgl_lahir', 'tempat_lahir', 'alamat', 'no_tlp', 'status_online', 'jenis_kelamin', 'status_pernikahan', 'agama', 'pekerjaan'];
        $isComplete = $data && collect($requiredFields)->every(fn($field) => !empty($data->$field));
        return response()->json(['complete' => $isComplete]);
    });


    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile-detail', ProfileDetail::class)->name('settings.profile-detail');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Route::resource('cabang', CabangController::class)->middleware('role:ADMIN,CABANG'); CONTOH jika banyak role

    // Chatify routes

    Route::post('/send', [ExtendedMessageController::class, 'send'])->name('send.message');
    Route::post('/chatify/fetchMessages', [ExtendedMessageController::class, 'fetch'])->name('fetch.messages');
});

Route::fallback(function () {
    abort(404);
});

require __DIR__ . '/auth.php';
