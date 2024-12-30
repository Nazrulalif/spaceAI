<?php

use App\Livewire\Classification\Classification;
use App\Livewire\Classification\FillMask;
use App\Livewire\Classification\ImageClassification;
use App\Livewire\Classification\TextClassification;
use App\Livewire\Claude\Claude;
use App\Livewire\Gemini\Gemini;
use App\Livewire\Gemma\Gemma;
use App\Livewire\Generate\ImageToText;
use App\Livewire\Generate\Summary;
use App\Livewire\Generate\TextToImage;
use App\Livewire\Groq\Groq;
use App\Livewire\Llama\Llama;
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
Route::get('/llama', Llama::class)->name('llama');
Route::get('/gemma', Gemma::class)->name('gemma');
Route::get('/generate-summary', Summary::class)->name('summary');
Route::get('/zero-shot-classification', Classification::class)->name('classification.zero-shot');
Route::get('/fill-mask', FillMask::class)->name('fillmask');
Route::get('/image-classification', ImageClassification::class)->name('classification.image');
Route::get('/sentiment', TextClassification::class)->name('classification.text');
Route::get('/text-to-image', TextToImage::class)->name('text_to_image');
Route::get('/image-to-text', ImageToText::class)->name('image_to_text');
// Route::get('/claudeai', action: Claude::class)->name('claude');
// Route::get('/openai', Index::class)->name('openai);
