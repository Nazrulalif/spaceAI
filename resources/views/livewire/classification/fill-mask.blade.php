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
                            <div class="chart-container pt-3" style="width: 100%; min-width: 108%; max-width: 100%;">
                                <canvas id="chart-{{ $index }}" wire:key="chart-{{ $index }}"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 20rem">
                        <div class="fs-5">
                            🌟🚀 Start your fill mask 🚀🌟
                        </div>
                    </div>
                    @endforelse
                </div>

                <div>
                    <form wire:submit.prevent="send" id="chatForm"
                        class="d-flex justify-content-center align-items-center gap-3 pb-2">
                        <a wire:click="clear" class="btn text-center trash fs-5" title="Clear all chat"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-trash"></i>
                        </a>
                        @if ($model === 'microsoft/deberta-v3-base'|| $model === 'google-bert/bert-base-uncased'|| $model === 'distilbert/distilbert-base-uncased')
                            @php
                                $mask_token = "Mask token: [MASK]";
                                $placeholder = "eg: Paris is the [MASK] of France";
                            @endphp
                        @else
                            @php
                                $mask_token = "Mask token: <mask>";
                                $placeholder = "eg: Paris is the <mask> of France";
                            @endphp
                        @endif

                        <div class="d-flex flex-column align-items-center w-100">
                            <small>{{$mask_token}}</small>
                        
                            <input type="text" wire:model="message" id="chatInput" class="form-control p-3 rounded-pill"
                                placeholder="{{$placeholder}}" autocomplete="off">
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

    <script>
         let charts = {};

        document.addEventListener('livewire:init', () => {
            // Initialize existing charts
            const results = @json($results);

            results.forEach((msg, index) => {
                if (msg.type === 'ai' && msg.content.labels && msg.content.scores) {
                    createOrUpdateChart(index, msg.content.labels, msg.content.scores);
                }
            });

          
            // Listen for new chart data
            Livewire.on('newChartDataFillMask', (eventData) => {
                const { chartId, labels, scores } = eventData.data;
                
                // Add a small delay to ensure the DOM is ready
                setTimeout(() => {
                    createOrUpdateChart(chartId, labels, scores);
                }, 100);
            });

            // Listen for clear charts event
            Livewire.on('clearChartsFillMask', () => {
                Object.values(charts).forEach(chart => chart.destroy());
                charts = {};
            });
        });

        function createOrUpdateChart(chartId, labels, scores) {
            const canvasId = `chart-${chartId}`;
            const canvas = document.getElementById(canvasId);

            if (!canvas) {
                console.error('Canvas not found:', canvasId);
                return;
            }

            // Destroy existing chart if it exists
            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            const ctx = canvas.getContext('2d');
            charts[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Prediction Scores',
                        data: scores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    animation: false,
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Fill Mask Predictions'
                        }
                    }
                }
            });
        }

        // Handle scroll functionality
        const chatWindow = document.getElementById('chatWindow');
        const scrollDownBtn = document.getElementById('scrollDownBtn');

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

    </script>
</div>
