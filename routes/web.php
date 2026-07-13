<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppointmentApplicationController;
use App\Http\Controllers\ResumeApplicationController;
use App\Http\Controllers\Admin\AppointmentApplicationController as AdminAppointmentApplicationController;
use App\Http\Controllers\Admin\ResumeApplicationController as AdminResumeApplicationController;

Route::get('/', FrontController::class)->name('front.home');
Route::get('/news', [FrontController::class, 'news'])->name('front.news');
Route::get('/news/{article}', [FrontController::class, 'article'])->name('front.news.show');
Route::get('/{locale}/news', [FrontController::class, 'localizedNews'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.news');
Route::get('/{locale}/news/{article}', [FrontController::class, 'localizedArticle'])
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale.news.show');
Route::get('/{locale}', FrontController::class)
    ->whereIn('locale', ['uz', 'ru', 'en'])
    ->name('front.locale');
Route::post('/resume-applications', [ResumeApplicationController::class, 'store'])->name('resume-applications.store');
Route::post('/appointment-applications', [AppointmentApplicationController::class, 'store'])->name('appointment-applications.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
        Route::put('/settings', [ContentController::class, 'updateSettings'])->name('settings.update');
        Route::get('/resumes', [AdminResumeApplicationController::class, 'index'])->name('resumes.index');
        Route::delete('/resumes/{resume}', [AdminResumeApplicationController::class, 'destroy'])->name('resumes.destroy');
        Route::get('/appointments', [AdminAppointmentApplicationController::class, 'index'])->name('appointments.index');
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
