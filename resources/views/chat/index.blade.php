<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center mb-4">
            <a href="{{ route('userdashboard') }}" class="group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mr-2 mb-4 group-hover:stroke-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold mb-4">Chat</h1>
        </div>
    </x-slot>

    <div class="flex flex-col h-full p-4">
        <!-- Chat messages container -->
        <div id="chat_container" class="space-y-4" style="overflow-y: auto; max-height: 80vh;">
            <!-- Display existing messages -->
            @foreach($messages as $message)
                <p class="border p-3 mb-4 {{ $message->sender_id == $adminId ? 'text-left bg-blue-200' : 'text-right bg-gray-200' }}">
                    {{ $message->message }}
                    <br>
                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($message->created_at)->format('H:i A') }}</span>
                </p>
            @endforeach
        </div>
        
        <!-- Input message form -->
        <form id="message_form" class="mt-auto">
            @csrf
            <div class="flex">
                <input type="hidden" name="recipient_id" value="{{ $adminId }}">
                <input id="message_input" type="text" name="message" placeholder="Type your message..." class="flex-1 p-2 border rounded-l-md focus:outline-none focus:ring focus:border-blue-300">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Send</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var isScrolledToBottom = true;
            var pollingInterval;


            function fetchMessages() {
                if (isScrolledToBottom) {
                    $.ajax({
                        url: '{{ route("chat.messages") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var sortedMessages = response.sort(function(a, b) {
                                return new Date(a.created_at) - new Date(b.created_at);
                            });
                            updateMessagesUI(sortedMessages);
                            $("#chat_container").scrollTop($("#chat_container")[0].scrollHeight);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            }

            function updateMessagesUI(messages) {
                $('#chat_container').empty();
                messages.forEach(function(message) {
                    var isAdminMessage = (message.sender_id == {{ $adminId }});
                    var messageClass = isAdminMessage ? 'text-left bg-blue-200' : 'text-right bg-gray-200';
                    var createdAt = new Date(message.created_at);
                    var formattedDate = createdAt.toLocaleString();

                    $('#chat_container').append('<p class="border p-3 mb-4 ' + messageClass + '">' +
                        message.message +
                        '<br>' +
                        '<span class="text-xs text-gray-500">' + formattedDate + '</span>' +
                        '</p>');
                });
            }
    
            pollingInterval = setInterval(fetchMessages, 5000);


            $('#message_form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.post('{{ route("chat.send") }}', formData, function(response) {
                });
                // Clear the input field
                $('#message_input').val('');
            });

            // Detect if user scrolled to bottom
            $("#chat_container").scroll(function() {
                var scrollHeight = $("#chat_container").prop("scrollHeight");
                var scrollTop = $("#chat_container").prop("scrollTop");
                var height = $("#chat_container").prop("clientHeight");

                isScrolledToBottom = scrollHeight - scrollTop === height;
                
                if (isScrolledToBottom && !pollingInterval) {
                    // Start polling again if scrolled to bottom
                    pollingInterval = setInterval(fetchMessages, 5000);
                }
            });
        });
    </script>
</x-app-layout>
