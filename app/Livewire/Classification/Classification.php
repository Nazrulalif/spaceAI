<?php

namespace App\Livewire\Classification;

use Livewire\Component;
use GuzzleHttp\Client;

class Classification extends Component
{
    public $model = 'facebook/bart-large-cnn';
    public $model_type;
    public $message;
    public $class;
    public $client;
    public $labels;
    public $scores;
    public $results=[];

    public function mount()
    {
        $this->model_type = [
            'facebook/bart-large-mnli' => 'facebook/bart-large-mnli',
        ];
        $this->results = session()->get('chat_history_classification', []);
    }

    public function clear()
    {
        session()->forget('chat_history_classification');
        $this->results = [];
        return redirect()->route('classification');
    }
    public function send(){
            $client = new Client([
                'base_uri' => 'https://api-inference.huggingface.co/models/',
                'headers' => [
                    'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_TOKEN'),
                ],
            ]);

             // Check if message is empty
             if (empty($this->message) || empty($this->class)) {
                return;
            }
    
            // Add user message to results
            $this->results[] = [
                'type' => 'user',
                'content' => [
                    'text'=> $this->message,
                    'class'=> $this->class,
                ]
            ];
  
        $candidateLabels = explode(',', $this->class);

        try {
            // Prepare the data for zero-shot classification
            $response = $client->post('facebook/bart-large-mnli', [
                'json' => [
                    'inputs' => $this->message,
                    'parameters' => [
                    'candidate_labels' => array_map('trim', $candidateLabels), 
                    ]
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $this->results[] = [
                'type' => 'ai',
                'content' => $data['scores'],
            ];

            // $this->labels = array_keys($data['labels']);
            // $this->scores = $data['scores'];  

        } catch (\Exception $e) {
            // Handle API errors
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }
        
        // Save the updated results to session to persist across requests
        session()->put('chat_history_classification', $this->results);

        $this->message = '';

    }
    public function render()
    {
        return view('livewire.classification.classification');
    }
}
