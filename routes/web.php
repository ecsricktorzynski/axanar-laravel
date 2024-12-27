
<?php
  
use Illuminate\Support\Facades\Route;
  
use Ares\Http\Controllers\Auth\AuthController;
use Ares\Http\Controllers\SiteController;
use Ares\Http\Controllers\VaultController;

  
Route::get('/', function () {
    return view('index');
});
Route::get('index', function () {
    return view('index');
});

Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::get('/dashboard', [SiteController::class, 'dashboard'])->name('dashboard');

Route::get('campaign', [AuthController::class, 'campaign']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('test', [SiteController::class, 'test'])->name('test');
Route::post('/test-csrf', function () {
    return 'CSRF OK';
})->name('test.csrf');

Route::prefix('vault')->group(function () {
    Route::get('asset/{hash}', [VaultController::class, 'asset'])->middleware('verified');
});
