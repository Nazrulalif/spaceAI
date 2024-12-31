<div>
    @include('livewire.partials.navbar')
    <div class="d-flex flex-column">
        <div class="px-1 px-md-5 pt-1">
            <div class="w-100">
                <div class="chat-window" id="chatWindow">


                    @forelse ($results as $index => $msg)
                    @if ($msg['type'] === 'user')
                    <div class="text-end my-4">
                        <div class="message">
                            <img src="{{ $msg['content']['url'] }}" alt="Uploaded Image" class="w-50 p-4 rounded"
                                style="background-color: #303030">
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
                            {{ $msg['content'] }}
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 20rem">
                        <div class="fs-5">
                            🌟🚀 Start your OCR 🚀🌟
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

                        <input type="file" wire:model="message" id="chatInput" accept=".jpeg, .png, .jpg"
                            class="form-control p-3 rounded-pill pe-5 ps-5" placeholder="Upload a file"
                            autocomplete="off">

                        <button type="submit" class="btn rounded-circle bg-light text-center" id="submitBtn"
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
        document.addEventListener('livewire:init', () => {
            const fileInput = document.getElementById('chatInput');
            const submitBtn = document.querySelector('button[type="submit"]');

            submitBtn.addEventListener('click', (e) => {
                const file = fileInput.files[0]; // Get file from the input element

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const dataURL = e.target.result;
                        recognizeFile(file); // Pass the file to OCR
                    };
                    reader.readAsDataURL(file);
                } else {
                    console.log('Error: No file selected');
                }
            });

            function recognizeFile(file) {
                $("#log").empty();
                const corePath = window.navigator.userAgent.indexOf("Edge") > -1 ?
                    '{{ asset("assets/js/ocr/tesseract-core.asm.js") }}' :
                    '{{ asset("assets/js/ocr/tesseract-core.wasm.js") }}';

                const worker = new Tesseract.TesseractWorker({
                    corePath,
                });

                let isProgressMessageShown = false;
                worker.recognize(file, 'eng')
                    .progress(function (packet) {
                        console.log(packet);
                        if (packet.status === 'recognizing text') {
                            if (!isProgressMessageShown) {
                                const aiDiv = document.createElement('div');
                                aiDiv.className = 'text-start my-2';
                                aiDiv.id = 'loadingIndicator'; // Add an ID for easy removal later
                                aiDiv.innerHTML = `<div class="message ai">Generating text...</div>`;

                                chatWindow.appendChild(aiDiv);

                                // Set the flag to true after appending the message
                                isProgressMessageShown = true;

                                // Scroll to the bottom
                                chatWindow.scrollTop = chatWindow.scrollHeight;
                            }

                        }
                      
                    })
                    .then(function (data) {
                        Livewire.dispatch('ocrCompleted', { text: data.text });
                        if (typeof callback === 'function') {
                            callback(data.text);
                        }
                    })
                    .catch(function (error) {
                        console.error('OCR Error:', error);

                        // If you need to call a callback, make sure it's defined
                        if (typeof callback === 'function') {
                            callback(null);
                        }
                    });
            }

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
</div>
