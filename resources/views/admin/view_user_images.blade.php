<x-admin-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Uploaded Images</h1>
            <a href="{{ route('customer') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Back to Customers</a>
        </div>
        @if($userImages->isEmpty())
            <p class="mt-4 text-gray-600">No images uploaded for this user.</p>
        @else
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($userImages as $image)
                    @if(!empty($image->valid_id1) && !empty($image->valid_id2))
                        <div class="bg-white rounded-lg overflow-hidden shadow-md">
                            <img src="{{ asset($image->valid_id1) }}" class="w-full">
                            <img src="{{ asset($image->valid_id2) }}" class="w-full">
                            <div class="flex justify-between mt-2">
                                <form action="{{ route('verify.image') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <input type="hidden" name="action" value="verify">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Verify</button>
                                </form>
                                <form action="{{ route('verify.image') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Reject</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
