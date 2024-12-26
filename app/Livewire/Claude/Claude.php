<?php

namespace App\Livewire\Claude;

use Livewire\Component;
use Anthropic\Laravel\Facades\Anthropic;
class Claude extends Component
{
    public $message;
    
    public function send(){

        $result = Anthropic::messages()->create([
            'model' => 'claude-3-haiku-20240307',
            'max_tokens' => 1024,
            'messages' => [
                ['role' => 'user', 'content' => 'hai'],
            ],
        ]);

        // Handle API response
        $data = $result->choices[0]->message->content ?? 'No response received';
        dd($data);
      
    }
    public function render()
    {
        return view('livewire.claude.claude');
    }
}
