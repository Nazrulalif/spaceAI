<?php

namespace App\Livewire\Classification;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageClassification extends Component
{
    use WithFileUploads;
    public $model = 'FacebookAI/roberta-base';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];

    public function mount()
    {
        $this->model_type = [
            'microsoft/resnet-50' => 'microsoft/resnet-50',
        ];

        $this->results = session()->get('chat_history_imageClass', []);
        $this->model = session()->get('model_classification_imageClass', 'facebook/bart-large-mnli');

    }

    public function clear()
    {
        session()->forget('chat_history_imageClass');
        $this->results = [];
        $this->dispatch('clearChartsImageClass');
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
        
    }


    public function render()
    {
        return view('livewire.classification.image-classification');
    }
}
