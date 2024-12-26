<?php

namespace App\Livewire\Llama;

use Livewire\Component;
use LucianoTonet\GroqLaravel\Facades\Groq;

class Llama extends Component
{
    
    public $message;
    public $results = []; // Stores all chat messages
    public $model = 'Llama-3.1-70b-versatile';
    public $model_type;

    public function mount()
    {
        $this->model_type = [
            'Llama-3.1-70b-versatile' => 'Llama-3.1-70b-versatile',
            'Llama-3.1-8b-instant' => 'Llama-3.1-8b-instant',
            'llama-guard-3-8b' => 'llama-guard-3-8b',
            'llama3-70b-8192' => 'llama3-70b-8192',
            'llama3-8b-8192' => 'llama3-8b-8192',
        ];

        $this->results = session()->get('chat_history_llama', []);
    }

    public function clear()
    {
        session()->forget('chat_history_llama');
        $this->results = [];
        return redirect()->route('llama');
    }

    public function send()
    {
        if (empty($this->message)) {
            return;
        }
        // Add user message to chat history
        $this->results[] = [
            'type' => 'user',
            'content' => $this->message,
        ];

        try {
          
            $response = Groq::chat()->completions()->create([
                'model' => $this->model,
                'messages' => [
                    [
                    'role' => 'user', 
                    'content' => $this->message
                    ],
                ],
            ]);
            
            $aiResponse = $response['choices'][0]['message']['content'] ?? 'No response available.';

            $this->results[] = [
                'type' => 'ai',
                'content' => $aiResponse,
            ];

        } catch (\Exception $e) {
            // Handle API errors
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        // Store the chat history in session
        session()->put('chat_history_llama', $this->results);

        // Clear the input field
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.llama.llama');
    }
}
