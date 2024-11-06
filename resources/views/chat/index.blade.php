<x-app-layout>
    <style>
        #chat-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid #e2e8f0; 
            background-color: #f7fafc;
            width: 400px; /* Set a fixed width */
            margin: 20px auto; /* Center chat on the page */
        }

        #message-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 10px;
            height: 300px; /* Set a fixed height for the message list */
        }

        .message {
            padding: 0.5rem;
            border-radius: 0.375rem;
            max-width: 70%;
            word-wrap: break-word;
        }

        .sender {
            align-self: flex-end;
            background-color: #d1fae5; 
            text-align: right;
        }

        .receiver {
            align-self: flex-start;
            background-color: #edf2f7;
            text-align: left;
        }

        #message-input-container {
            display: flex;
            padding: 0.5rem;
            border-top: 1px solid #e2e8f0; 
            background-color: #ffffff;
        }

        #message-input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #e2e8f0; 
            border-radius: 0.375rem;
            font-size: 14px; /* Smaller font */
        }

        #send-button {
            padding: 0.5rem 1rem;
            background-color: #3b82f6;
            color: white;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 14px; /* Smaller button font */
            margin-left: 0.5rem;
        }

        #send-button:hover {
            background-color: #2563eb;
        }
    </style>

    <div id="chat-container">
        <div id="message-list"></div>
        <div id="message-input-container">
            <input type="text" id="message-input" placeholder="Type a message" class="flex-1 mr-2">
            <button id="send-button">Send</button>
        </div>
    </div>

    <script>
        window.authUserId = @json(auth()->id());
        window.authUserType = @json(auth()->user()->usertype);

        // Assuming the admin's user ID is known and fixed
        const adminUserId = 1; // Replace with your actual admin ID
        let currentReceiverId = adminUserId; // Directly set it to the admin

        async function fetchMessages() {
            if (!currentReceiverId) return;

            try {
                const response = await fetch(`/get-messages?receiver_id=${currentReceiverId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const messages = await response.json();
                console.log(messages);
                const messageList = document.getElementById('message-list');

                messageList.innerHTML = '';

                messages.forEach(msg => {
                    const msgElement = document.createElement('div');
                    msgElement.className = `message ${msg.sender_id === window.authUserId ? 'sender' : 'receiver'}`;
                    msgElement.textContent = msg.content;
                    messageList.appendChild(msgElement);
                });
                messageList.scrollTop = messageList.scrollHeight;
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('send-button');

            button.addEventListener('click', async () => {
                const messageInput = document.getElementById('message-input');
                const message = messageInput.value;

                if (message.trim() !== '' && currentReceiverId) {
                    await fetch('/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message, receiver_id: currentReceiverId })
                    });
                    messageInput.value = '';
                    fetchMessages();
                }
            });

            fetchMessages(); // Fetch messages on load
            setInterval(fetchMessages, 2000); // Poll for new messages
        });
    </script>
</x-app-layout>
