<div>
    @include('livewire.partials.navbar')

    <div class="d-flex flex-column">
        <div class="px-1 px-md-5 pt-1">
            <div class="w-100">
                <div class="chat-window" id="chatWindow" >
                    {{-- @forelse ($results as $index => $msg)
                    @if ($msg['type'] === 'user')
                    <div class="text-end my-4">
                        <div class="message user">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                    @elseif ($msg['type'] === 'system')
                    <div class="text-start my-4">
                        <div class="message ai">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                    @else
                    <div class="text-start my-4">
                        <div class="message ai">
                            <img src="{{ $msg['content'] }}" alt="Uploaded Image" class="w-50 p-4 rounded" style="background-color: #303030">
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 20rem">
                        <div class="fs-5">
                            🌟🚀 Start your generating text with image 🚀🌟
                        </div>
                    </div>
                    @endforelse --}}
                </div>

                <div>
                    <form wire:submit.prevent="send" id="chatForm"
                        class="d-flex justify-content-center align-items-center gap-3 pb-3">
                        <a wire:click="clear" class="btn text-center trash fs-5" title="Clear all chat"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-trash"></i>
                        </a>
                        
                        <input type="text" wire:model="message" id="chatInput" class="form-control p-3 rounded-pill"
                        placeholder="Type your message" autocomplete="off">

                        <button type="submit" class="btn rounded-circle bg-light text-center"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button id="scrollDownBtn" class="btn btn-dark rounded-circle " style="display: none;">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
