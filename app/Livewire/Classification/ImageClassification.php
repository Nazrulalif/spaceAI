<?php

namespace App\Livewire\Classification;

use Livewire\Component;
use Livewire\WithFileUploads;
use GuzzleHttp\Client;

class ImageClassification extends Component
{
    use WithFileUploads;
    public $model = 'Falconsai/nsfw_image_detection';
    public $model_type;
    public $message;
    public $client;
    public $scores;
    public $results = [];
    public function mount()
    {
        $this->model_type = [
            'microsoft/resnet-50' => 'microsoft/resnet-50',
            'google/vit-base-patch16-224' => 'google/vit-base-patch16-224',
            'Falconsai/nsfw_image_detection' => 'Falconsai/nsfw_image_detection',
            'nateraw/vit-age-classifier' => 'nateraw/vit-age-classifier',
        ];

        $this->results = session()->get('chat_history_imageClass', []);
        $this->model = session()->get('model_classification_imageClass', 'Falconsai/nsfw_image_detection');

    }

    public function clear()
    {
        session()->forget('chat_history_imageClass');
        $this->results = [];
        $this->dispatch('clearChartsImageClass');
    }

    public function send(){

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
          
            // Read the file contents as binary
            $imageBinary = file_get_contents($this->message->getRealPath());
    
            $response = $client->post($this->model, [
                'body' => $imageBinary, // Send raw binary data
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            // Extract labels and scores from the API response
            $labels = array_map(function($item) {
                return $item['label'];
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
            
            // Dispatch event with the processed data
            $this->dispatch('newChartDataImageClass', data: [
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

       

        session()->put('chat_history_imageClass', $this->results);
        session()->put('model_classification_imageClass', $this->model);

        $this->message = null;

    }


    public function render()
    {
        return view('livewire.classification.image-classification');
    }
}
