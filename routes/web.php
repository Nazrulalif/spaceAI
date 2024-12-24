<?php

use App\Livewire\Gemini\Gemini;
use App\Livewire\Openai\Index;
use Illuminate\Support\Facades\Route;

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

Route::get('/', Gemini::class)->name('gemini');
// Route::get('/openai', Index::class)->name('openai);
