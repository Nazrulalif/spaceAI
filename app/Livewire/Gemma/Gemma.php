<?php

namespace App\Livewire\Gemma;

use Livewire\Component;
use LucianoTonet\GroqLaravel\Facades\Groq;

class Gemma extends Component
{
    
    public $message;
    public $results = []; // Stores all chat messages
    public $model = 'gemma2-9b-it';
    public $model_type;

    public function mount()
    {
        $this->model_type = [
            'gemma2-9b-it' => 'gemma2-9b-it',
        ];

        $this->results = session()->get('chat_history_gemma', []);
    }

    public function clear()
    {
        session()->forget('chat_history_gemma');
        $this->results = [];
        return redirect()->route('gemma');
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
                'content' => $this->formatGemmaResponse($aiResponse),
            ];

        } catch (\Exception $e) {
            // Handle API errors
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        // Store the chat history in session
        session()->put('chat_history_gemma', $this->results);

        // Clear the input field
        $this->message = '';
    }
    public function formatGemmaResponse($text)
    {
        // Convert **bold** to <strong>bold</strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);

        // Replace * with a line break if it has space around it
        $text = preg_replace('/\s\*\s/', '<br>', $text);

        // Ensure any remaining * without spaces are handled (optional)
        $text = preg_replace('/\*/', '<br>', $text);
        

        return $text;
    }
    public function render()
    {
        return view('livewire.gemma.gemma');
    }
}
