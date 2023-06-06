<?php

use App\Http\Controllers\SMSManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/sms",[SMSManagementController::class,"showSMSPage"]);

Route::any("/send",[SMSManagementController::class,"calculateSMSCharges"]);
