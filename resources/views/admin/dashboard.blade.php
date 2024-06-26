<x-admin-layout>
    <div class="flex flex-col w-full h-screen p-6">
        <!-- Header -->
        <div class="flex flex-row justify-between w-full h-10">
            <!-- Overview -->
            <div>
                <h1 class="text-2xl font-semibold p-auto">
                    Overview
                </h1>
            </div>

            <!-- Todays date -->
            <div class="flex flex-row items-center justify-center h-full gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <h1 id="todaysDate" class="font-medium">

                </h1>

            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col h-5/6 w-full gap-5">
            <!-- Top -->
            <div class="flex flex-row items-center justify-evenly h-1/2 w-full">
                <!-- Left -->
                <div class="flex flex-col justify-between w-5/12 h-full bg-white border-white shadow-md rounded-md" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                    <!-- Top -->
                    <div class="flex flex-row items-center w-3/5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                        <h1 class="text-lg font-semibold">
                            Registered Users
                        </h1>
                    </div>

                    <div class="flex flex-row w-2/5 h-5/6 items-center justify-center gap-2">
                        <h1 class="text-6xl font-semibold text-red-500">
                            {{-- {{ $userCount }} --}}
                        </h1>
                        <p class="text-2xl font-medium mt-5">
                            {{ __('Users') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center w-full h-2/5">
                        <h1>View All</h1>
                    </div>
                </div>
                <!-- Right -->
                <div class="w-5/12 h-full rounded-md" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;"">
                    <!-- Top -->
                    <div class="flex flex-row w-full justify-between p-2">
                        <h1 class="text-lg font-semibold">
                            Page Views
                        </h1>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Bottom -->
            <div class="flex flex-row items-center justify-evenly h-1/2 w-full">
                <!-- Left -->
                <div class="w-3/5 h-full bg-white border-white shadow-md rounded-md" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                    <!--Top -->
                    <div class="flex flex-row w-full justify-between p-2">
                        <h1 class="text-lg font-semibold">
                            Recent Orders
                        </h1>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </div>
                </div>
                <!-- Right -->
                <div class="w-2/5 h-full rounded-md" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;"">
                    <!--Top -->
                    <div class="flex flex-row w-full justify-between p-2">
                        <h1 class="text-lg font-semibold">
                            Analytics
                        </h1>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const currentDate = new Date();
        const options = {day: 'numeric', month: 'long', year: 'numeric'}
        const formattedDate = currentDate.toLocaleDateString('en-US', options)


        document.getElementById('todaysDate').innerText = formattedDate;
    </script>
</x-admin-layout>