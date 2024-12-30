<?php

namespace App\Livewire\Classification;

use Livewire\Component;
use GuzzleHttp\Client;

class TextClassification extends Component
{
    public $model = 'ProsusAI/finbert';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];
    public function mount()
    {
        $this->model_type = [
            'ProsusAI/finbert' => 'ProsusAI/finbert',
            'distilbert/distilbert-base-uncased-finetuned-sst-2-english' => 'distilbert/distilbert-base-uncased-finetuned-sst-2-english',
        ];

        $this->results = session()->get('chat_history_textClass', []);
        $this->model = session()->get('model_classification_textClass', 'ProsusAI/finbert');

    }

    public function clear()
    {
        session()->forget('chat_history_textClass');
        $this->results = [];
    }

    public function send(){
        
        if (empty($this->message)) {
            return;
        }

        
        $this->results[] = [
            'type' => 'user',
            'content' => $this->message,
        ];

        // Get the current index for the loading state
        $currentIndex = count($this->results);
        
        // Add placeholder for AI response
        $this->results[] = [
            'type' => 'ai',
            'content' => [
                'labels' => [],
                'scores' => []
            ]
        ];

            $client = new Client([
                'base_uri' => 'https://api-inference.huggingface.co/models/',
                'headers' => [
                    'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_TOKEN'),
                ],
            ]);
            
            $response = $client->post($this->model, [
                'json' => [
                    'inputs' => $this->message,
                ]
            ]);
    
            $data = json_decode($response->getBody(), true);

            $classificationData = $data[0]; // Extract the first element which contains the relevant data

            $labels = array_map(function ($item) {
                return $item['label'];
            }, $classificationData);

            $scores = array_map(function ($item) {
                return $item['score'];
            }, $classificationData);

            // Update the placeholder with actual data
            $this->results[count($this->results) - 1] = [
                'type' => 'ai',
                'content' => [
                    'labels' => $labels,
                    'scores' => $scores,
                    'raw_response' => $data
                ]
            ];

            // dd($data);
            
            // Dispatch event with the processed data
            $this->dispatch('newChartDataTextClass', data: [
                'chartId' => $currentIndex,
                'labels' => $labels,
                'scores' => $scores
            ]);
    
      
        
        session()->put('chat_history_textClass', $this->results);
        session()->put('model_classification_textClass', $this->model);

        $this->message = '';
    }
    public function render()
    {
        return view('livewire.classification.text-classification');
    }
}
