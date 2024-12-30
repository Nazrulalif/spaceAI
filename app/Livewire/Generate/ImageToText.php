<?php

namespace App\Livewire\Generate;

use Livewire\Component;

class ImageToText extends Component
{
    public $model = 'Salesforce/blip-image-captioning-large';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];
    public function mount()
    {
        $this->model_type = [
            'Salesforce/blip-image-captioning-large' => 'Salesforce/blip-image-captioning-large',
        ];

        $this->results = session()->get('chat_history_imageToText', []);
        $this->model = session()->get('model_classification_textClass', 'Salesforce/blip-image-captioning-large');

    }

    public function clear()
    {
        session()->forget('chat_history_textClass');
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.generate.image-to-text');
    }
}
