<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Member\ComplaintController as MemberComplaintController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Global\UserController as GlobalUserController;
use App\Http\Controllers\Member\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes([
  'verify' => false,
  'register' => false,
]);

Route::get('/', function () {
  return view('content.global.index');
})->name('landing');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

  Route::prefix('members')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->name('admin.members.index');
    Route::get('/create', [MemberController::class, 'create'])->name('admin.members.create');
    Route::post('/import', [MemberController::class, 'import'])->name('admin.members.import');
    Route::post('/', [MemberController::class, 'store'])->name('admin.members.store');
    Route::get('/{id}/detail/pdf', [MemberController::class, 'generatePdf'])->name('admin.members.detail.pdf');
    Route::get('/{id}/detail', [MemberController::class, 'detail'])->name('admin.members.detail');
    Route::get('/{id}/edit', [MemberController::class, 'edit'])->name('admin.members.edit');
    Route::put('/{id}', [MemberController::class, 'update'])->name('admin.members.update');
    Route::delete('/{id}', [MemberController::class, 'destroy'])->name('admin.members.destroy');
  });

  Route::prefix('parts')->group(function () {
    Route::get('/', [PartController::class, 'index'])->name('admin.parts.index');
    Route::post('/', [PartController::class, 'store'])->name('admin.parts.store');
    Route::put('/{id}', [PartController::class, 'update'])->name('admin.parts.update');
    Route::delete('/{id}', [PartController::class, 'destroy'])->name('admin.parts.destroy');
  });

  Route::prefix('complaints')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/{id}/detail', [ComplaintController::class, 'detail'])->name('admin.complaints.detail');
    Route::get('/{id}/detail/pdf', [ComplaintController::class, 'generatePdf'])->name('admin.complaints.detail.pdf');
    Route::put('/{id}', [ComplaintController::class, 'update'])->name('admin.complaints.update');
    Route::delete('/{id}', [ComplaintController::class, 'delete'])->name('admin.complaints.delete');
  });

  Route::prefix('contracts')->group(function () {
    Route::post('/', [ContractController::class, 'store'])->name('admin.contracts.store');
  });

  Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
  Route::get('/users/{id}/detail', [UserController::class, 'detail'])->name('admin.users.detail');
  Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
  Route::delete('/users/{id}', [UserController::class, 'delete'])->name('admin.users.delete');

  Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.admin.index');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.admin.store');
    Route::put('/{id}', [AdminController::class, 'update'])->name('admin.admin.update');
    Route::delete('/{id}', [AdminController::class, 'delete'])->name('admin.admin.delete');
  });

  Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/{id}/update-kta', [SettingController::class, 'updateKta'])->name('admin.settings.update-kta');
    Route::get('/kta/print', [SettingController::class, 'printKta'])->name('admin.settings.kta.print');
  });
});

Route::prefix('member')->middleware(['auth', 'member'])->group(function () {
  Route::prefix('complaints')->group(function () {
    Route::get('/', [MemberComplaintController::class, 'index'])->name('member.complaints.index');
    Route::post('/', [MemberComplaintController::class, 'store'])->name('member.complaints.store');
    Route::get('/{id}/detail', [MemberComplaintController::class, 'detail'])->name('member.complaints.detail');
    Route::get('/{id}/detail/pdf', [MemberComplaintController::class, 'generatePdf'])->name('member.complaints.detail.pdf');
    Route::put('/{id}', [MemberComplaintController::class, 'update'])->name('member.complaints.update');
    Route::put('/{id}/status', [MemberComplaintController::class, 'updateStatus'])->name('member.complaints.update.status');
    Route::delete('/{id}', [MemberComplaintController::class, 'destroy'])->name('member.complaints.destroy');
  });

  Route::get('/profile', [ProfileController::class, 'index'])->name('member.profile.index');
  Route::get('/profile/pdf', [ProfileController::class, 'generatePdf'])->name('member.profile.pdf');
  Route::get('/profile/kta/print', [ProfileController::class, 'printKTA'])->name('member.profile.kta');
  Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('member.profile.update');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');
