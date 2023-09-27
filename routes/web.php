<?php

use App\Livewire\Staff\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowStaffProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('staff/{staff}/profile', ShowStaffProfileController::class)->name('staff.profile');
