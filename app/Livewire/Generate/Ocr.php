<?php

namespace App\Livewire\Generate;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Ocr extends Component
{
    use WithFileUploads;
    
    public $model = 'tesseract.js';
    public $model_type;
    public $message;
    public $client;
    public $results=[];

    public function mount()
    {
        $this->model_type = [
            'tesseract.js' => 'tesseract.js',
        ];

        $this->results = session()->get('chat_history_ocr', []);
        $this->model = session()->get('model_classification_ocr', 'tesseract.js');

    }
    public function clear()
    {
        session()->forget('chat_history_ocr');
        $this->results = [];
    }
    
    public function send(){
        if (empty($this->message)) {
            return;
        }

        $this->results[] = [
            'type' => 'user',
            'content' => [
                'type' => 'image',
                'url' => $this->message->temporaryUrl(),
            ],
        ];
        session()->put('chat_history_ocr', $this->results);

        $this->dispatch('submitted');

        $this->message = null;

    }

    #[On('ocrCompleted')]
    public function handleOcrResult($text)
    {

        if ($text != null || $text != ''){
            $this->results[] = [
                'type' => 'ai',
                'content' => $text
            ];
        }else{
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, no text recognized',
            ];
        }
       

        session()->put('chat_history_ocr', $this->results);
    }
    public function render()
    {
        return view('livewire.generate.ocr');
    }
}
