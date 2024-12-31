<?php

use App\Livewire\Classification\Classification;
use App\Livewire\Classification\FillMask;
use App\Livewire\Classification\ImageClassification;
use App\Livewire\Classification\TextClassification;
use App\Livewire\Claude\Claude;
use App\Livewire\Gemini\Gemini;
use App\Livewire\Gemma\Gemma;
use App\Livewire\Generate\ImageToText;
use App\Livewire\Generate\Ocr;
use App\Livewire\Generate\Summary;
use App\Livewire\Generate\TextToImage;
use App\Livewire\Groq\Groq;
use App\Livewire\Llama\Llama;
use App\Livewire\Openai\Index;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
|                  2024
|                 Credits
|---------------------------------------------------------------------------
| Developed by Muhammad Nazrul Alif
| GitHub: https://github.com/Nazrulalif/
| Website: https://nazrulalif.vercel.app/
| WhatsApp: 014-9209024
|
| Feel free to explore and contribute to this project!
|---------------------------------------------------------------------------
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
Route::get('/ocr', Ocr::class)->name('ocr');

