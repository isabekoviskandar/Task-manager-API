<?php

use App\Console\Commands\Check;
use App\Livewire\LoginComponent;
use App\Livewire\ProductComponent;
use App\Livewire\RegisterComponent;
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

\Illuminate\Support\Facades\Schedule::command(\App\Console\Commands\Check::class)->everyFiveSeconds();
