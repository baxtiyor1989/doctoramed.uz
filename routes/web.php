<?php

use App\Http\Controllers\Admin\AppointmentApplicationController as AdminAppointmentApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeployController;
use App\Http\Controllers\Admin\ResumeApplicationController as AdminResumeApplicationController;
use App\Http\Controllers\Admin\ServiceRatingController as AdminServiceRatingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppointmentApplicationController;
use App\Http\Controllers\AppointmentCaptchaController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ResumeApplicationController;
use App\Http\Controllers\ResumeCaptchaController;
use App\Http\Controllers\ServiceRatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', FrontController::class)->name('front.home');
Route::get('/services/filter', [FrontController::class, 'filterServices'])->name('front.services.filter');
Route::get('/media', function (Request $request) {
    $path = ltrim((string) $request->query('path'), '/');

    abort_if($path === '' || str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path));
})->name('media.show');
Route::get('/media/{path}', function (string $path) {
    $path = ltrim($path, '/');

    abort_if(str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path));
})->where('path', '.*')->name('media.legacy');
Route::get('/news', [FrontController::class, 'news'])->name('front.news');
Route::get('/news/{article}', [FrontController::class, 'article'])->name('front.news.show');
Route::get('/doctors', [FrontController::class, 'doctors'])->name('front.doctors.index');
Route::get('/doctors/{doctor}', [FrontController::class, 'doctor'])->name('front.doctors.show');
Route::get('/{locale}/news', [FrontController::class, 'localizedNews'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.news');
Route::get('/{locale}/news/{article}', [FrontController::class, 'localizedArticle'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.news.show');
Route::get('/{locale}/doctors/{doctor}', [FrontController::class, 'localizedDoctor'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.doctors.show');
Route::get('/{locale}/doctors', [FrontController::class, 'localizedDoctors'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.doctors.index');
Route::get('/{locale}/services/filter', [FrontController::class, 'localizedFilterServices'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.services.filter');
Route::get('/{locale}', FrontController::class)
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale');
Route::post('/resume-applications', [ResumeApplicationController::class, 'store'])->name('resume-applications.store');
Route::get('/resume-captcha', ResumeCaptchaController::class)->name('resume.captcha');
Route::post('/appointment-applications', [AppointmentApplicationController::class, 'store'])->name('appointment-applications.store');
Route::get('/appointment-captcha', AppointmentCaptchaController::class)->name('appointment.captcha');
Route::get('/service-ratings', [ServiceRatingController::class, 'status'])->name('service-ratings.status');
Route::post('/service-ratings', [ServiceRatingController::class, 'store'])->middleware('throttle:10,1')->name('service-ratings.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::get('/captcha', [AuthController::class, 'captcha'])->name('captcha');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/deploy', [DeployController::class, 'index'])->name('deploy.index');
        Route::post('/deploy', [DeployController::class, 'store'])->name('deploy.store');
        Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
        Route::get('/branches-vacancies', [ContentController::class, 'branchesVacancies'])->name('branches-vacancies');
        Route::get('/regions-districts', [ContentController::class, 'regionsDistricts'])->name('regions-districts');
        Route::put('/settings', [ContentController::class, 'updateSettings'])->name('settings.update');
        Route::post('/clear-cache', [ContentController::class, 'clearCache'])->name('clear-cache');
        Route::get('/resumes', [AdminResumeApplicationController::class, 'index'])->name('resumes.index');
        Route::delete('/resumes/{resume}', [AdminResumeApplicationController::class, 'destroy'])->name('resumes.destroy');
        Route::get('/appointments', [AdminAppointmentApplicationController::class, 'index'])->name('appointments.index');
        Route::get('/service-ratings', [AdminServiceRatingController::class, 'index'])->name('ratings.index');
        Route::delete('/appointments/{appointment}', [AdminAppointmentApplicationController::class, 'destroy'])->name('appointments.destroy');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/content/{resource}', [ContentController::class, 'index'])->name('content.index');
        Route::get('/content/{resource}/create', [ContentController::class, 'create'])->name('content.create');
        Route::post('/content/{resource}', [ContentController::class, 'store'])->name('content.store');
        Route::get('/content/{resource}/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/content/{resource}/{id}', [ContentController::class, 'update'])->name('content.update');
        Route::delete('/content/{resource}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
