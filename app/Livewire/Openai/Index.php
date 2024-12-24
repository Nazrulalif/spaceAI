<?php

namespace App\Livewire\Openai;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;
class Index extends Component
{
    public $message;
    public function send(){
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!'],
            ],
        ]);

        $data = $result->choices[0]->message->content;
        
        dd( $data);
    }
    public function render()
    {
        return view('livewire.openai.index');
    }
}
