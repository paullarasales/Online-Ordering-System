<x-admin-layout>
    <style>
        #container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        #sidebar {
            width: 40%;
            padding: 1rem;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
        }

        #chat-container-wrapper {
            width: 60%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            border: 1px solid #e2e8f0;
            background-color: #f7fafc;
            overflow-y: auto;
        }

        #message-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            gap: 5px;
        }

        #message-input-container {
            display: flex;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background-color: #ffffff;
            position: sticky; /* Keeps the input container fixed */
            bottom: 0;
            width: 100%;
            z-index: 10; /* Ensures it stays on top of the messages */
        }

        .message {
            padding: 0.5rem;
            border-radius: 0.375rem;
            max-width: 70%;
            word-wrap: break-word;
        }

        .sender {
            align-self: flex-end;
            background-color: #4169E1;
            text-align: right;
            color: white;
        }

        .receiver {
            align-self: flex-start;
            background-color: #edf2f7;
            text-align: left;
        }

        #message-input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }

        #send-button {
            margin-left: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #3b82f6;
            color: white;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
        }

        #send-button:hover {
            background-color: #2563eb;
        }

        #selected-customer {
            padding: 1rem;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f7fafc;
        }

        .user-list-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: start;
            flex-direction: row;
            gap: 10px;
        }

        .user-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #ffffff;
            font-weight: bold;
        }

        .user-photo-placeholder {
            background-color: #3b82f6;
        }

        .user-name {
            font-weight: normal;
        }
    </style>

    <div id="container">
        <!-- Sidebar -->
        <div id="sidebar">
            <h3 class="text-lg font-semibold mb-4">Customer</h3>
            <div id="user-list">
            </div>
        </div>

        <!-- Chat Container -->
        <div id="chat-container-wrapper">
            <div id="selected-customer">
                Select a customer to start chatting
            </div>
            <div id="chat-container">
                <div id="message-list">
                </div>
                <div id="message-input-container" style="display: none;">
                    <input type="text" id="message-input" placeholder="Type a message" class="flex-1 mr-2">
                    <button id="send-button">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.authUserId = @json(auth()->id());
        window.authUserType = @json(auth()->user()->usertype);
        let currentReceiverId = null

        async function fetchUserList() {
            try {
                const response = await fetch('/get-users');
                const users = await response.json();
                console.log(users);
                const userList = document.getElementById('user-list');

                userList.innerHTML = '';

                users.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.className = 'user-list-item';

                    // Create the photo element
                    const userPhotoElement = document.createElement('div');
                    userPhotoElement.className = 'user-photo';

                    if (user.photo) {
                        // Create an image element if photo URL is available
                        const imgElement = document.createElement('img');
                        imgElement.src = user.photo;
                        imgElement.className = 'user-photo';
                        imgElement.onerror = function() {
                            // Fallback to text if image fails to load
                            this.style.display = 'none'; // Hide the image
                            userPhotoElement.classList.add('user-photo-placeholder');
                            userPhotoElement.textContent = user.name.charAt(0); // Display the first letter of the name
                        };
                        userPhotoElement.appendChild(imgElement);
                    } else {
                        // Use text-based fallback
                        userPhotoElement.classList.add('user-photo-placeholder');
                        userPhotoElement.textContent = user.name.charAt(0); // Display the first letter of the name
                    }

                    userElement.appendChild(userPhotoElement);

                    // Create the name element
                    const userNameElement = document.createElement('span');
                    userNameElement.textContent = user.name;
                    userNameElement.className = 'user-name';
                    userNameElement.style.fontWeight = user.new_messages_count > 0 ? 'bold' : 'normal';

                    userElement.appendChild(userNameElement);
                    userElement.dataset.userId = user.id;

                    userElement.addEventListener('click', async () => {
                        currentReceiverId = user.id;
                        document.getElementById('selected-customer').textContent = `Chatting with ${user.name}`;
                        fetchMessages();

                        // Show message input and send button
                        document.getElementById('message-input-container').style.display = 'flex';

                        // Mark messages as read
                        await fetch('/mark-messages-as-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ receiver_id: user.id })
                        });

                        // Update the user list to remove bolding
                        fetchUserList();
                    });

                    userList.appendChild(userElement);
                });
            } catch (error) {
                console.error('Error fetching user list:', error);
            }
        }

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
                    messageList.appendChild(msgElement)
                });
                messageList.scrollTop = messageList.scrollHeight - messageList.clientHeight;
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

            fetchUserList();
            setInterval(fetchUserList, 2000);
            setInterval(fetchMessages, 2000);
        });
    </script>
</x-admin-layout>
