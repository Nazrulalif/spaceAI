<?php

namespace App\Livewire\Generate;

use Livewire\WithFileUploads;
use Livewire\Component;
use GuzzleHttp\Client;

class ImageToText extends Component
{
    use WithFileUploads;

    public $model = 'Salesforce/blip-image-captioning-large';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];
    public function mount()
    {
        $this->model_type = [
            'Salesforce/blip-image-captioning-large' => 'Salesforce/blip-image-captioning-large',
            'nlpconnect/vit-gpt2-image-captioning' => 'nlpconnect/vit-gpt2-image-captioning',
            'microsoft/git-large-coco' => 'microsoft/git-large-coco',
        ];

        $this->results = session()->get('chat_history_imageToText', []);
        $this->model = session()->get('model_classification_imageToText', 'Salesforce/blip-image-captioning-large');

    }

    public function clear()
    {
        session()->forget('chat_history_imageToText');
        $this->results = [];
    }

    public function send(){

        // Check if message is empty
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
       
        try {
            $client = new Client([
                'base_uri' => 'https://api-inference.huggingface.co/models/',
                'headers' => [
                    'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_TOKEN'),
                ],
            ]);
          
            // Read the file contents as binary
            $imageBinary = file_get_contents($this->message->getRealPath());
    
            $response = $client->post($this->model, [
                'body' => $imageBinary, // Send raw binary data
            ]);
    
            $data = json_decode($response->getBody(), true);


            $caption = $data[0]['generated_text'] ?? 'No caption generated.';

            $this->results[] = [
                'type' => 'ai',
                'content' => $caption,
            ];
         
        } catch (\Exception $e) {
            logger()->error('Caption Error:', ['error' => $e->getMessage()]);
             // Handle API errors
             $this->results[] = [
                'type' => 'ai',
                'content' => 'Sorry, I encountered an error. Please try again.',
            ];
        }

        session()->put('chat_history_imageToText', $this->results);
        session()->put('model_classification_imageToText', $this->model);

        $this->message = null;
    }

    public function render()
    {
        return view('livewire.generate.image-to-text');
    }
}
