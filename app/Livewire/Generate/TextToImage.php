<?php

namespace App\Livewire\Generate;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class TextToImage extends Component
{
    
    public $model = 'black-forest-labs/FLUX.1-dev';
    public $model_type;
    public $message;
    public $client;
    public $results=[];
    public function mount()
    {
        $this->model_type = [
            'black-forest-labs/FLUX.1-schnell' => 'black-forest-labs/FLUX.1-schnell',
            'black-forest-labs/FLUX.1-dev' => 'black-forest-labs/FLUX.1-dev',
            'stabilityai/stable-diffusion-3.5-large' => 'stabilityai/stable-diffusion-3.5-large',
            'Shakker-Labs/FLUX.1-dev-LoRA-Logo-Design' => 'Shakker-Labs/FLUX.1-dev-LoRA-Logo-Design',
        ];
        $this->results = session()->get('chat_history_textToImage', []);
        $this->model = session()->get('model_textToImage', 'black-forest-labs/FLUX.1-dev');

    }
    public function clear()
    {
        session()->forget('chat_history_textToImage');
        $this->results = [];
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

            // Send the request to Hugging Face
            $response = $client->post($this->model, [
                'json' => [
                    'inputs' => $this->message,
                ],
            ]);

            // Extract the binary content of the image
            $imageContent = $response->getBody()->getContents();

            // Save the image locally
            $filePath = 'generated_images/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filePath, $imageContent);

            // Add the generated image to results
            $this->results[] = [
                'type' => 'ai',
                'content' => asset('storage/' . $filePath),
            ];
        } catch (\Exception $e) {
            // Log the error and show a friendly message
            logger()->error('Hugging Face API Error:', ['error' => $e->getMessage()]);
            $this->results[] = [
                'type' => 'error',
                'content' => 'An error occurred while generating the image. Please try again later.',
            ];
        }

        // Optionally clear the message after sending
        $this->message = '';
         
        session()->put('chat_history_textToImage', $this->results);

    }

    public function render()
    {
        return view('livewire.generate.text-to-image');
    }
}
