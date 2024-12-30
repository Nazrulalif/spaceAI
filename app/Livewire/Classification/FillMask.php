<?php

namespace App\Livewire\Classification;


use Livewire\Component;
use GuzzleHttp\Client;

class FillMask extends Component
{
    public $model = 'FacebookAI/roberta-base';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];

    public function mount()
    {
        $this->model_type = [
            'FacebookAI/roberta-base' => 'FacebookAI/roberta-base',
            // 'FacebookAI/xlm-roberta-base' => 'FacebookAI/xlm-roberta-base',
            'microsoft/deberta-v3-base' => 'microsoft/deberta-v3-base',
            // 'google-bert/bert-base-uncased' => 'google-bert/bert-base-uncased',
            // 'distilbert/distilbert-base-uncased' => 'distilbert/distilbert-base-uncased',

        ];
        $this->results = session()->get('chat_history_fillMask', []);
        $this->model = session()->get('model_classification_fillMask', 'FacebookAI/roberta-base');

    }

    public function clear()
    {
        session()->forget('chat_history_fillMask');
        $this->results = [];
        $this->dispatch('clearChartsFillMask');
    }

    public function send()
    {
        if (empty($this->message)) {
            return;
        }

        if($this->model === 'microsoft/deberta-v3-base' || $this->model === 'google-bert/bert-base-uncased'|| $this->model === 'distilbert/distilbert-base-uncased'){
            if (strpos($this->message, '[MASK]') === false) {
                $this->results[] = [
                    'type' => 'user',
                    'content' => $this->message,
                ];
                $this->results[] = [
                    'type' => 'system',
                    'content' => 'Your input must include "[MASK]". Please try again.',
                ];
                $this->message = '';
                return;
            }
        }else{
            if (strpos($this->message, '<mask>') === false) {
                $this->results[] = [
                    'type' => 'user',
                    'content' => $this->message,
                ];
                $this->results[] = [
                    'type' => 'system',
                    'content' => 'Your input must include "<mask>". Please try again.',
                ];
                $this->message = '';
                return;
            }
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

        try {
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
            
            // Extract labels and scores from the API response
            $labels = array_map(function($item) {
                return $item['token_str'];
            }, $data);
            
            $scores = array_map(function($item) {
                return $item['score'];
            }, $data);

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
            $this->dispatch('newChartDataFillMask', data: [
                'chartId' => $currentIndex,
                'labels' => $labels,
                'scores' => $scores
            ]);
    
        } catch (\Exception $e) {
            logger()->error('Fill Mask Error:', ['error' => $e->getMessage()]);
            $this->results[count($this->results) - 1] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }
        
        session()->put('chat_history_fillMask', $this->results);
        session()->put('model_classification_fillMask', $this->model);

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.classification.fill-mask');
    }
}