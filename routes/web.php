<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('divisi', App\Http\Controllers\Divisi::class);
Route::resource('karyawan', App\Http\Controllers\Karyawan::class);
Route::resource('schedule', App\Http\Controllers\Schedule::class);
Route::get('/showdivisi', [App\Http\Controllers\Schedule::class, 'showdivisi'])->name('showdivisi');
Route::get('/schedule/calendar/{id}', [App\Http\Controllers\Schedule::class, 'showcalendar'])->name('showcalendar');
