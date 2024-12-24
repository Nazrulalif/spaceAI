<?php

namespace App\Livewire\Gemini;

use Livewire\Component;
use Gemini\Laravel\Facades\Gemini as GeminiAPI;

class Gemini extends Component
{ 
    public $message;
    public $results = []; // Stores all chat messages
    public function mount()
    {
        // Load chat history from localStorage when the component is mounted
        $this->results = session()->get('chat_history_gemini', []);  // Load saved chat history from session
    }
    public function clear()
    {
        session()->forget('chat_history_gemini');
        $this->results = [];
        return redirect()->route('gemini');
    }
    
    public function send()
    {
        if (empty($this->message)) {
            return;
        }

        // Add the user message to the messages array
        $this->results[] = [
            'type' => 'user',
            'content' => $this->message,
        ];

        // Call Gemini API
        try {
            $response = GeminiAPI::geminiPro()->generateContent($this->message);

            // Add AI response to the messages array
            $this->results[] = [
                'type' => 'ai',
                'content' => $this->formatGeminiResponse($response->text()),
            ];
        } catch (\Exception $e) {
            // Handle API errors
            $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        // Store the chat history in session
        session()->put('chat_history_gemini', $this->results);

        // Clear the input field
        $this->message = '';
    }
    public function formatGeminiResponse($text)
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
        return view('livewire.gemini.gemini');
    }
}
