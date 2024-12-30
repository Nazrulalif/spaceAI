<?php

namespace App\Livewire\Generate;

use Livewire\Component;
use GuzzleHttp\Client;

class Summary extends Component
{
    public $model = 'facebook/bart-large-cnn';
    public $model_type;
    public $message;
    public $client;
    public $results=[];

    public function mount()
    {
        $this->model_type = [
            'facebook/bart-large-cnn' => 'facebook/bart-large-cnn',
            'utrobinmv/t5_summary_en_ru_zh_base_2048' => 'utrobinmv/t5_summary_en_ru_zh_base_2048',
        ];
        $this->results = session()->get('chat_history_summary', []);

    }
    public function clear()
    {
        session()->forget('chat_history_summary');
        $this->results = [];
        return redirect()->route('summary');
    }
    public function send()
    {
        // Ensure client is initialized properly
        $client = new Client([
            'base_uri' => 'https://api-inference.huggingface.co/models/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_TOKEN'),
            ],
        ]);

        // Check if message is empty
        if (empty($this->message)) {
            return;
        }

        // Add user message to results
        $this->results[] = [
            'type' => 'user',
            'content' => $this->message,
        ];

        try {
            // Send the text input for summarization
            $response = $client->post($this->model, [
                'json' => [
                    'inputs' => $this->message,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Add the summary to the results array
            $summary = $data[0]['summary_text'] ?? 'No summary generated.';

            $this->results[] = [
                'type' => 'ai',
                'content' => $summary,
            ];

        } catch (\Exception $e) {
            // Handle API errors
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        // Save the updated results to session to persist across requests
        session()->put('chat_history_summary', $this->results);

        // Optionally clear the message after sending
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.generate.summary');
    }
}
