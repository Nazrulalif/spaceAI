<div>
    @include('livewire.partials.navbar')

    <div class="d-flex flex-column ">
        <div class="px-1 px-md-5 pt-1">
            <div class="w-100">
                <div class="chat-window" id="chatWindow" style="max-height: 60vh; min-height: 60vh">
                    @forelse ($results as $msg)
                    @if ($msg['type'] === 'user')
                    <div class="text-end my-4">
                        <div class="message user">

                            <div class="fs-6 fw-bold">Zero-Shot Classification</div>
                            {{ $msg['content']['text'] }}
                            <br>
                            <div class="fs-6 fw-bold">Possible class names</div>
                            {{ $msg['content']['class'] }}
                        </div>
                    </div>
                    @else
                    <div class="text-start my-4">
                        <div class="message ai">
                            {{ is_array($msg['content']) ? json_encode($msg['content']) : htmlspecialchars($msg['content']) }}

                            <div class="chart-container pt-3">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 20rem">
                        <div class="fs-5">
                            🌟🚀 Start your zero-shot classification 🚀🌟
                        </div>
                    </div>
                    @endforelse

                </div>
                <div>
                    <form wire:submit.prevent='send' id="chatForm"
                        class="d-flex justify-content-center align-items-center gap-3">
                        <a wire:click='clear' class="btn text-center text-white trash fs-5" title="Clear all chat"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-trash"></i>
                        </a>

                        <div class="d-flex flex-column gap-2 w-100 pb-4">
                            <textarea wire:model.live='message' rows="1" id="chatInput"
                                class="form-control p-3 rounded-pill" placeholder="Zero-Shot Classification"
                                autocomplete="off"></textarea>
                            <input type="text" wire:model.live='class' id="chatInput2"
                                class="form-control p-3 rounded-pill"
                                placeholder="Possible class names (comma-separated)" autocomplete="off">
                        </div>

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
    const chatInput2 = document.getElementById('chatInput2');
    const scrollDownBtn = document.getElementById('scrollDownBtn');

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const userMessage = chatInput.value.trim();
        if (!userMessage) return;

        const userMessage2 = chatInput2.value.trim();
        if (!userMessage2) return;

        // Append user message
        const userDiv = document.createElement('div');
        userDiv.className = 'text-end my-2';
        userDiv.innerHTML = `<div class="message user">
                <div class="fs-6 fw-bold">Zero-Shot Classification</div>
                    ${userMessage}
                <br>
                <div class="fs-6 fw-bold">Possible class names</div>
                    ${userMessage2}
            </div>`;
        chatWindow.appendChild(userDiv);

        chatInput.value = '';

        setTimeout(() => {
            const aiDiv = document.createElement('div');
            aiDiv.className = 'text-start my-2';
            aiDiv.innerHTML = `<div class="message ai">Classify data...</div>`;
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
<script>
    const labels = @json($labels);
    const scores = @json($scores);
    console.log(@json($scores)); 
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',  // Bar chart
        data: {
            labels: labels,
            datasets: [{
                label: 'Classification Scores',
                data: scores,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>