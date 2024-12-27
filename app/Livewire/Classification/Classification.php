<?php

namespace App\Livewire\Classification;

use Livewire\Component;
use GuzzleHttp\Client;

class Classification extends Component
{
    public $model = 'facebook/bart-large-mnli';
    public $model_type;
    public $message;
    public $class;
    public $client;
    public $labels;
    public $scores;
    public $results = [];

    protected $listeners = ['chartUpdated'];

    public function mount()
    {
        $this->model_type = [
            'facebook/bart-large-mnli' => 'facebook/bart-large-mnli',
            'MoritzLaurer/DeBERTa-v3-base-mnli-fever-anli' => 'MoritzLaurer/DeBERTa-v3-base-mnli-fever-anli',
        ];
        $this->results = session()->get('chat_history_classification', []);
        $this->model = session()->get('model_classification', 'facebook/bart-large-mnli');
    }

    public function clear()
    {
        session()->forget('chat_history_classification');
        $this->results = [];
        $this->dispatch('clearCharts');
    }

    public function send()
    {
        if (empty($this->message) || empty($this->class)) {
            return;
        }

        $this->results[] = [
            'type' => 'user',
            'content' => [
                'text' => $this->message,
                'class' => $this->class,
            ]
        ];

        $client = new Client([
            'base_uri' => 'https://api-inference.huggingface.co/models/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_TOKEN'),
            ],
        ]);

        $candidateLabels = explode(',', $this->class);

        try {
            $response = $client->post($this->model, [
                'json' => [
                    'inputs' => $this->message,
                    'parameters' => [
                        'candidate_labels' => array_map('trim', $candidateLabels),
                    ]
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            
            // Debug the API response
            logger()->info('API Response:', $data);
            
            $chartData = [
                'type' => 'ai',
                'content' => [
                    'labels' => $data['labels'] ?? [],
                    'scores' => $data['scores'] ?? [],
                ]
            ];
            
            $this->results[] = $chartData;
            
            // Store the current index
            $currentIndex = count($this->results) - 1;
            
            // Dispatch event with explicit data
            $this->dispatch('newChartData', data: [
                'chartId' => $currentIndex,
                'labels' => $chartData['content']['labels'],
                'scores' => $chartData['content']['scores']
            ]);

        } catch (\Exception $e) {
            logger()->error('Classification Error:', ['error' => $e->getMessage()]);
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        session()->put('chat_history_classification', $this->results);
        session()->put('model_classification', $this->model);

        $this->message = '';
        $this->class = '';
    }

    public function render()
    {
        return view('livewire.classification.classification');
    }
}