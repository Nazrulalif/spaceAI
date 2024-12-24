<div>
    <div class="d-flex flex-column ">
        <div class="px-1 px-md-5 pt-3">
            <div class="w-100">
                <div class="chat-window" id="chatWindow">
                    <!-- Chat messages will go here -->
                    <div class="text-end my-2">
                        <div class="message user bg-light">Hello, AI!</div>
                    </div>
                    <div class="text-start my-2">
                        <div class="message ai">Hi! How can I assist you?</div>
                    </div>
                </div>
                <div >
                    <form wire:submit.prevent='send' id="chatForm" class="d-flex justify-content-center align-items-center gap-3">
                        <input type="text" wire:model='text' id="chatInput" class="form-control p-3 rounded-pill" placeholder="Type your message..." autocomplete="off">
                        <button type="submit" class="btn rounded-circle bg-light text-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    
                </div>
            </div>
        </div>
        <script>
            const chatForm = document.getElementById('chatForm');
            const chatWindow = document.getElementById('chatWindow');
            const chatInput = document.getElementById('chatInput');

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

                // Simulate AI response
                setTimeout(() => {
                    const aiDiv = document.createElement('div');
                    aiDiv.className = 'text-start my-2';
                    aiDiv.innerHTML = `<div class="message ai">This is a simulated response.</div>`;
                    chatWindow.appendChild(aiDiv);

                    // Scroll to the bottom
                    chatWindow.scrollTop = chatWindow.scrollHeight;
                }, 1000);
            });

        </script>
    </div>
</div>
