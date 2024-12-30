<div>
    @include('livewire.partials.navbar')

    <div class="d-flex flex-column">
        <div class="px-1 px-md-5 pt-1">
            <div class="w-100">
                <div class="chat-window" id="chatWindow" >
                    @forelse ($results as $index => $msg)
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
                            🌟🚀 Start your generating image 🚀🌟
                        </div>
                    </div>
                    @endforelse
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
<script>
    document.addEventListener('livewire:init', () => {
        const chatForm = document.getElementById('chatForm');
        const chatWindow = document.getElementById('chatWindow');
        const chatInput = document.getElementById('chatInput');
        const scrollDownBtn = document.getElementById('scrollDownBtn');

        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const userMessage = chatInput.value.trim();
            if (!userMessage) return;

            // Append user message
            const userDiv = document.createElement('div');
            userDiv.className = 'text-end my-2';
            userDiv.innerHTML = `<div class="message user">${userMessage}</div>`;
            chatWindow.appendChild(userDiv);

            // Append loading indicator
            const aiDiv = document.createElement('div');
            aiDiv.className = 'text-start my-2';
            aiDiv.id = 'loadingIndicator';
            aiDiv.innerHTML = `
            <div class="text-start my-4 ms-2">
                <div class="message ai" class="w-50 p-4 rounded" style="background-color: #303030">
                    <div class="spinner-border m-5" role="status">
                        <span class="visually-hidden">Generating image...</span>
                    </div>
                </div>
            </div>
            `;
            chatWindow.appendChild(aiDiv);

            chatInput.value = '';
            chatWindow.scrollTop = chatWindow.scrollHeight;
        });

        // Scroll functionality
        chatWindow.addEventListener('scroll', () => {
            if (chatWindow.scrollHeight - chatWindow.scrollTop > chatWindow.clientHeight + 100) {
                scrollDownBtn.style.display = 'block';
            } else {
                scrollDownBtn.style.display = 'none';
            }
        });

        scrollDownBtn?.addEventListener('click', () => {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        });

        // Handle Livewire response updates
        Livewire.hook('message.processed', () => {
            const loadingIndicator = document.getElementById('loadingIndicator');
            if (loadingIndicator) {
                // Remove the loading indicator after the response is updated
                loadingIndicator.remove();
            }

            // Ensure chat window scrolls to the bottom
            chatWindow.scrollTop = chatWindow.scrollHeight;
        });
    });

</script>