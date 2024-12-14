<x-app-layout>
    <!-- Notification Banner -->
    @if(session('success'))
        <div id="notification-banner" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md">
            {{ session('success') }}
        </div>
    @elseif($errors->any())
        <div id="notification-banner" class="fixed top-0 right-0 m-4 p-4 bg-red-500 text-white rounded-md">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Main content -->
    <div class="container mx-auto py-4 max-w-5xl">
        <!-- Product Display Section -->
        <div id="product-list" class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @if($products->isEmpty())
                <div class="text-center text-gray-600">No products to display.</div>
            @else
                @foreach($products as $product)
                    <div class="w-full sm:w-1/2 md:w-auto md:h-86 lg:w-54 mb-4 flex">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-full flex flex-col justify-between">
                            <img src="{{ asset($product->photo) }}" alt="Product Image" class="w-full h-32 md:h-28 object-cover sm:h-32">
                            <div class="p-4 flex flex-col justify-between flex-grow">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                                    <p class="text-sm text-gray-600">Price: ₱{{ $product->price }}</p>
                                    <p class="text-sm text-gray-600">Description: {{ $product->description }}</p>
                                </div>
                                @if(!$product->availability)
                                    <button class="bg-red-500 text-white px-4 py-2 rounded-md" onclick="showUnavailableModal('{{ $product->product_name }}')">View Details</button>
                                @else
                                    <form action="{{ route('add-to-cart', ['productId' => $product->id]) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Add to Cart</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Verification ID Modal -->
        <div id="verification-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
                <h2 class="text-lg font-bold mb-4">Verification Required</h2>
                <p class="text-gray-700 mb-6">Please upload a verification ID to continue.</p>
                <a href="{{ route('verify.form') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Upload Now</a>
                <button id="close-modal" class="ml-4 bg-gray-300 px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="pagination">
            {{ $products->links('vendor.pagination.tailwind') }}
        </div>

        <!-- Chat Box -->
        <div id="chat-container" class="fixed bottom-10 right-10 w-96 h-80 hidden flex-col bg-white border border-gray-300 shadow-lg">
            <div id="message-list" class="flex-1 overflow-y-auto p-4">
                <!-- Message -->
            </div>
            <div id="message-input-container" class="flex p-2 border-t border-gray-200 bg-gray-100">
                <input type="text" id="message-input" placeholder="Type a message" class="flex-1 p-2 border rounded-md">
                <button id="send-button" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md">Send</button>
            </div>
        </div>

        <button id="toggle-chat" class="fixed bottom-10 right-10 bg-blue-500 text-white px-4 py-2 rounded-md relative">
            Chat
            <span id="message-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
        </button>
    </div>

    <style>
        /* Positioning and Animation for Chat Button */
        #toggle-chat {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #toggle-chat:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #toggle-chat:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.6); /* Focus outline */
        }

        /* Chat container positioning */
        #chat-container {
            position: fixed;
            right: 20px;
            bottom: 80px; /* Initially set bottom above the button */
            width: 380px;
            height: 320px;
            display: none; /* Hidden by default */
            z-index: 999; /* Just below the button */
            transition: bottom 0.3s ease; /* Smooth transition when toggling */
        }

        /* Mobile responsiveness for chat button */
        @media (max-width: 640px) {
            #toggle-chat {
                right: 10px;
                bottom: 10px;
                padding: 10px 15px; /* Smaller padding for mobile */
            }

            #chat-container {
                right: 10px;
                bottom: 70px; 
                width: 90%;
                max-width: 400px;
            }
        }
        
        /* General styling for messages */
        #message-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto; /* Enable scrolling if messages overflow */
            max-height: calc(100% - 60px); /* Adjust to make space for the input area */
        }
        
        .message {
            max-width: 75%;
            padding: 8px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            word-wrap: break-word;
            white-space: pre-wrap; /* Ensure long words wrap */
        }
        
        /* Styling for sender's messages */
        .message.sender {
            background-color: #DCF8C6;
            align-self: flex-end; /* Align the sender's messages to the right */
            text-align: right;
        }
        
        /* Styling for receiver's messages */
        .message.receiver {
            background-color: #ECECEC;
            align-self: flex-start; /* Align the receiver's messages to the left */
            text-align: left;
        }
        
        #message-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e53e3e; /* Red color for the badge */
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            display: none; /* Hidden by default */
        }
    </style>

    <script>
        window.authUserId = {{ auth()->id() }};
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.getElementById('chat-container');
            const toggleChatButton = document.getElementById('toggle-chat');
            const sendButton = document.getElementById('send-button');
            const messageInput = document.getElementById('message-input');
            const messageList = document.getElementById('message-list');
            const messageElement = document.getElementById('message-count');
            const verificationModal = document.getElementById('verification-modal');
            const closeModalButton = document.getElementById('close-modal');

            // @if(session('upload_verification'))
            //     verificationModal.classList.remove('hidden');
            // @endif

            closeModalButton.addEventListener('click', () => {
                verificationModal.classList.add('hidden');
            });
            
            let isScrolledToBottom = true;
            
            messageList.addEventListener('scroll', () => {
                const atBottom = messageList.scrollHeight - messageList.clientHeight <= messageList.scrollTop + 1;
                isScrolledToBottom = atBottom;
            });
            

            toggleChatButton.addEventListener('click', async () => {
                if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                    chatContainer.style.display = 'flex'; 
                    chatContainer.style.bottom = '80px'; 
                    
                    fetch('/update-notified-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            notifiedbyuser: true 
                        })
                    })
                    .then(response => response.json())
                    .then(data => console.log(data))
                    .catch(error => console.error('Error:', error));
                } else {
                    chatContainer.style.display = 'none';
                }
            });


            sendButton.addEventListener('click', async () => {
                const message = messageInput.value.trim();
                if (message) {
                    await fetch('/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message, receiver_id: 1 }) 
                    });
                    messageInput.value = '';
                    fetchMessages();
                }
            });

            async function fetchMessages() {
                const response = await fetch(`/get-messages?receiver_id=1`);
                if (response.ok) {
                    const messages = await response.json();
                    messageList.innerHTML = ''; // Clear the existing messages
                    messages.forEach(msg => {
                        const msgElement = document.createElement('div');
                        // Set the correct class based on the sender_id
                        msgElement.className = `message ${msg.sender_id === window.authUserId ? 'sender' : 'receiver'}`;
                        msgElement.textContent = msg.content; // Add message content
                        messageList.appendChild(msgElement);
                    });
                    if (isScrolledToBottom) {
                        messageList.scrollTop = messageList.scrollHeight;
                    }
                }
            }
            
            async function getMessageCount() {
                try {
                    const response = await fetch('/user/unread-messages');
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
        
                    const data = await response.json();
                    console.log('Fetched count:', data);
                    const countMessage = data.unreadMessage;
        
                    if (messageElement && countMessage > 0) {
                        messageElement.textContent = countMessage;
                        messageElement.style.display = 'inline-block';
                    } else if (messageElement) {
                        messageElement.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error fetching unread messages:', error);
                }
            }

            // Periodically check for new messages
            setInterval(getMessageCount, 5000); // Adjust interval as needed

            fetchMessages();
            setInterval(fetchMessages, 2000);
        });
        function showUnavailableModal(productName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
                    <h2 class="text-lg font-bold mb-4">Product Unavailable</h2>
                    <p class="text-gray-700 mb-6">Sorry, the product "${productName}" is currently not available.</p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg" id="close-modal">Close</button>
                </div>
            `;
            document.body.appendChild(modal);

            const closeModalButton = modal.querySelector('#close-modal');
            closeModalButton.addEventListener('click', function() {
                modal.remove();
            });
        }
    </script>
</x-app-layout>