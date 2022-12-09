<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return view('template.template');
    }else{
        return view('auth.login');
    }
    
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('divisi', App\Http\Controllers\Divisi::class);
Route::get('/deletediv', [App\Http\Controllers\Divisi::class, 'deletediv'])->name('deletediv');
Route::resource('karyawan', App\Http\Controllers\Karyawan::class);
Route::get('/deletekary', [App\Http\Controllers\Karyawan::class, 'deletekary'])->name('deletekary');
Route::resource('schedule', App\Http\Controllers\Schedule::class);
Route::get('/showdivisi', [App\Http\Controllers\Schedule::class, 'showdivisi'])->name('showdivisi');
Route::get('/deleteData', [App\Http\Controllers\Schedule::class, 'deletedata'])->name('deletedata');
Route::get('/schedule/calendar/{id}', [App\Http\Controllers\Schedule::class, 'showcalendar'])->name('showcalendar');
Route::get('/schedule/detail-calendar/{id}', [App\Http\Controllers\Schedule::class, 'calendarview'])->name('calendarview');
Route::get('/deletedetail', [App\Http\Controllers\Schedule::class, 'deletedetail'])->name('deletedetail');

Route::get('/export-excel', [App\Http\Controllers\Schedule::class, 'exportExcel'])->name('exportExcel');