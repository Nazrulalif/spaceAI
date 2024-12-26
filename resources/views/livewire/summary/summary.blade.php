<div>
    @include('livewire.partials.navbar')

    <div class="d-flex flex-column ">
        <div class="px-1 px-md-5 pt-1">
            <div class="w-100">
                <div class="chat-window" id="chatWindow" >

                    @forelse ($results as $msg)
                    @if ($msg['type'] === 'user')
                    <div class="text-end my-4">
                        <div class="message user">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                    @else
                    <div class="text-start my-4" >
                        <div class="message ai">
                            {!! $msg['content'] !!}
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 20rem">
                        <div class="fs-5">
                            🌟🚀 Start your first summary 🚀🌟
                        </div>
                    </div>
                    @endforelse

                </div>
                <div>
                    <form wire:submit.prevent='send' id="chatForm"
                        class="d-flex justify-content-center align-items-center gap-3 pb-4">
                        <a wire:click='clear' class="btn text-center text-white fs-5" title="Clear all chat"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-trash"></i>
                        </a>
                        <textarea wire:model.live='message' rows="1"
                        id="chatInput"  class="form-control p-3 rounded-pill" 
                        placeholder="Type your message to summarize..." autocomplete="off"></textarea>
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
    document.addEventListener('livewire:load', function () {
        // Load chat history from localStorage when page is loaded
        const storedResults = localStorage.getItem('chat_history_summary');
        if (storedResults) {
            const results = JSON.parse(storedResults);
            results.forEach(message => {
                appendMessage(message);
            });
        }

        // Save chat history to localStorage whenever it is updated
        Livewire.hook('message.processed', (message, component) => {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.scrollTop = chatWindow
                .scrollHeight; // Scroll to the bottom after messages update

            // Save the current chat history to localStorage
            const chatHistory = @this.results; // Fetch the results from Livewire component
            localStorage.setItem('chat_history_summary', JSON.stringify(chatHistory));
        });
    });

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

        chatInput.value = '';

        setTimeout(() => {
            const aiDiv = document.createElement('div');
            aiDiv.className = 'text-start my-2';
            aiDiv.innerHTML = `<div class="message ai">Summarizing text...</div>`;
            chatWindow.appendChild(aiDiv);

            // Scroll to the bottom
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }, 1000);

    });

    // Show/Hide Scroll Down Button based on scroll position
    chatWindow.addEventListener('scroll', () => {
        if (chatWindow.scrollHeight - chatWindow.scrollTop > chatWindow.clientHeight + 100) {
            scrollDownBtn.style.display = 'block';
        } else {
            scrollDownBtn.style.display = 'none';
        }
    });

    // Scroll to the bottom when the button is clicked
    scrollDownBtn.addEventListener('click', () => {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    });

    // Ensure chat window is scrolled to the bottom on page load
    Livewire.hook('message.processed', () => {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    });

</script>
