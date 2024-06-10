<x-admin-layout>
    <style>
        /* Message styling */
        .message {
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 8px;
            max-width: 80%;
        }

        .customer-message {
            background-color: #3490dc;
            color: #fff;
            align-self: flex-start;
        }

        .admin-message {
            background-color: #d1d5db;
            color: #000;
            align-self: flex-end;
        }

        /* Message container styling */
        .message-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>

    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="w-1/4 p-4 overflow-y-auto">
            <h3 class="text-lg font-semibold mb-4">Customers</h3>
            <div class="space-y-2">
                @foreach($customerMessages->groupBy('sender_id') as $senderId => $senderMessages)
                    @if(!$senderMessages->first()->sender->isAdmin())
                        <div class="cursor-pointer message-sidebar" data-sender-id="{{ $senderId }}" onclick="toggleMessages('{{ $senderId }}')">
                            <h4 class="text-gray-800">{{ $senderMessages->first()->sender->name }}</h4>
                            <p class="text-sm text-gray-600">Last Message: {{ $senderMessages->last()->created_at->format('M d, H:i A') }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Message Container -->
        <div class="flex-1 bg-gray-100 p-4 overflow-y-auto">
            @foreach($customerMessages->groupBy('sender_id') as $senderId => $senderMessages)
                @if(!$senderMessages->first()->sender->isAdmin())
                    <div id="messages_{{ $senderId }}" class="message-container hidden">
                        <div class="message-list">
                            @php

                                $allMessages = $senderMessages->merge($adminMessages->where('recipient_id', $senderId));

                                $sortedMessages = $allMessages->sortBy('created_at');
                            @endphp
                            @foreach($sortedMessages as $message)
                                <div class="message @if($message->sender->isAdmin()) admin-message @else customer-message @endif" data-message-id="{{ $message->id }}">
                                    <p>{{ $message->message }}</p>
                                    <span class="text-xs">{{ $message->created_at->format('H:i A') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <form action="{{ route('chat.respond') }}" method="post" class="p-4">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $senderId }}">
                            <div class="flex">
                                <input type="text" name="message" placeholder="Type your message..." class="flex-1 p-2 border rounded-l-md focus:outline-none focus:ring focus:border-blue-300">
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Send</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <script>
        function toggleMessages(senderId) {
            var allMessageContainers = document.querySelectorAll('.message-container');
            allMessageContainers.forEach(function(container) {
                if (container.id === 'messages_' + senderId) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            });
        }


        function fetchNewMessages(senderId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var newMessages = response.messages;
                        appendNewCustomerMessages(senderId, newMessages);
                    } else {
                        console.error('Failed to fetch new messages for sender ID ' + senderId + '. Status: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', '/admin/messages?sender_id=' + senderId, true);
            xhr.send();
        }


        function appendNewCustomerMessages(senderId, newMessages) {
            var messageContainer = document.getElementById('messages_' + senderId);
            var messageList = messageContainer.querySelector('.message-list');


            var displayedMessageIds = Array.from(messageList.children).map(function(child) {
                return parseInt(child.getAttribute('data-message-id'));
            });

            newMessages.forEach(function(message) {

                if (!displayedMessageIds.includes(message.id)) {
                    var messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.classList.add('customer-message');
                    messageElement.setAttribute('data-message-id', message.id);

                    var messageText = document.createElement('p');
                    messageText.textContent = message.message;

                    let messageTime = document.createElement('span');
                    let createdAt = new Date(message.created_at);

                    // Format the time with AM/PM
                    let hours = createdAt.getHours();
                    let minutes = createdAt.getMinutes();
                    let amPM = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;

                    let formattedTime = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + amPM;

                    messageTime.textContent = formattedTime;
                    messageTime.classList.add('text-xs');



                    messageElement.appendChild(messageText);
                    messageElement.appendChild(messageTime);
                    messageList.appendChild(messageElement);

                    // Add the message ID to the list of displayed message IDs
                    displayedMessageIds.push(message.id);
                }
            });

            // Scroll to the bottom of the message list
            messageList.scrollTop = messageList.scrollHeight;
        }

        // Function to check for new messages periodically
        setInterval(function() {
            var visibleMessageContainer = document.querySelector('.message-container:not(.hidden)');
            if (visibleMessageContainer) {
                var senderId = visibleMessageContainer.id.split('_')[1];
                fetchNewMessages(senderId);
            }
        }, 5000); // Check every 5 seconds
    </script>
</x-admin-layout>
