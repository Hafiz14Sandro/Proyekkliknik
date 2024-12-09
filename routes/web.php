<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MahasiswaController;
use \App\Http\Controllers\DosenController;
use \App\Http\Controllers\MatakuliahController;
use \App\Http\Controllers\PasienController;

Route::resource('matakuliah', MatakuliahController::class);
Route::resource('pasien', PasienController::class);
Route::get('mahasiswa', [MahasiswaController::class,'index']);
Route::get('mahasiswa/create', [MahasiswaController::class,'create']);
Route::get('dosen', [DosenController::class,'index']);
Route::get('dosen/tambah', [DosenController::class,'tambah']);


Route::get('profile',function(){
    return '<h1>hello bro</h1>';
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
