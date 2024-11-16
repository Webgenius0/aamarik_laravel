@extends('backend.app')

@section('title', 'Chat | ' . $setting->title ?? 'SIS')

@push('styles')
@endpush

@section('content')
    <main>
        <!-- Content -->
        <div class="relative">
            <div class="py-14 h-[calc(100vh-252px)] scroll-container" >

                <div class="content">
                    <!-- Title -->
                    <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto text-center">
                        <a href="#" class="flex justify-center mb-6">
                            <img src="{{ asset($setting->logo ?? 'backend/assets/images/sis-logo.png') }}" class="h-20"
                                alt="logo">
                        </a>

                        <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl">
                            Replay your thoughts
                        </h1>
                    </div>
                    <!-- End Title -->

                    <ul class="mt-16 space-y-5" id="messageWrapper">
                        @forelse ($messages as $message)
                            @if ($message->type == 'admin')
                                <li class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                                    <div class="space-y-3 ml-auto">
                                        <h2 class="font-medium text-right text-gray-800">
                                            <span
                                                class="btn bg-danger rounded-full text-white ">{{ auth()->user()->name ?? 'SIS' }}</span>
                                        </h2>
                                        <div class="space-y-1.5 ">
                                            <p class="mb-1.5 me-2 text-sm text-gray-800 text-right">
                                                {{ $message->message }}
                                            </p>
                                        </div>
                                    </div>
                                    <img src="{{ asset(asset($setting->logo ?? 'backend/assets/images/sis-logo.png')) }}"
                                        alt="logo" class="h-16 rounded-full w-16">
                                </li>
                            @else
                                <li class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                                    <img src="{{ asset($message->user->avatar ?? 'backend/assets/images/avatar.png') }}"
                                        alt="logo" class="h-16 rounded-full w-16">

                                    <div class="space-y-3">
                                        <h2 class="font-medium text-gray-800">
                                            {{ $message->user->name }}
                                        </h2>
                                        <div class="space-y-1.5">
                                            <p class="mb-1.5 text-sm text-gray-800">
                                                {{ $message->message }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endif

                        @empty
                        @endforelse

                    </ul>
                </div>
            </div>

            <!-- Search -->
            <div class="sticky bottom-0 z-10 bg-white border-t border-gray-200 p-4">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                    <!-- Input -->
                    <div class="relative">
                        <form id="messageForm">
                            <input id="message"
                                class="p-4 pb-12 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Replay ..."></input>
                            <input type="hidden" name="id" id="id" value="{{ $room_id }}">
                            <!-- Toolbar -->
                            <div class="absolute bottom-px inset-x-px p-2 rounded-b-md bg-white">
                                <div class="flex justify-between items-center">
                                    <!-- Button Group -->
                                    <div class="flex items-center">
                                    </div>
                                    <!-- End Button Group -->

                                    <!-- Button Group -->
                                    <div class="flex items-center gap-x-1">
                                        <!-- Send Button -->
                                        <button type="submit"
                                            class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                            <i class="uil uil-message text-lg"></i>
                                        </button>
                                        <!-- End Send Button -->
                                    </div>
                                    <!-- End Button Group -->
                                </div>
                            </div>
                        </form>
                        <!-- End Toolbar -->
                    </div>
                    <!-- End Input -->
                </div>
            </div>
            <!-- End Search -->
        </div>
        <!-- End Content -->
    </main>
@endsection


@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    <script>
        $('#messageForm').submit(function(e) {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('chat.store') }}",
                data: {
                    message: $('#message').val(),
                    room_id: $('#id').val()
                },
                success: function(resp) {

                    if (resp.success === true) {
                        flasher.success(resp.message);

                        let wrapper = $('#messageWrapper');
                        let html = `<li class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                            <div class="space-y-3 ml-auto">
                                <h2 class="font-medium text-right text-gray-800">
                                    <span
                                        class="btn bg-danger rounded-full text-white ">{{ auth()->user()->name ?? 'SIS' }}</span>
                                </h2>
                                <div class="space-y-1.5 ">
                                    <p class="mb-1.5 text-sm text-gray-800 text-right">
                                        ${resp.message}
                                    </p>
                                </div>
                            </div>
                            <img src="{{ asset(asset($setting->logo ?? 'backend/assets/images/sis-logo.png')) }}"
                                alt="logo" class="h-16 rounded-full w-16">
                        </li>`;
                        wrapper.append(html);
                        $('#messageForm #message').val('');
                        scrollBottomWrapper();
                    } else {
                        if (resp.code === 422) {
                            flasher.warning(resp.message);
                        }
                    }


                },
                error: function(error) {
                    console.log(error);
                }
            });
        });


        const wrapper = document.querySelector('.scroll-container');

        console.log('Initial height:', wrapper.scrollHeight);


        function scrollBottomWrapper() {
            const wrapper = document.querySelector('.scroll-container');
            
           
            wrapper.style.overflowY='auto'

            // Log the initial scroll values
            console.log('Initial height:', wrapper.scrollHeight);
            console.log('Initial top:', wrapper.scrollTop);

            // Set the scroll position to the bottom
            wrapper.scrollTop = wrapper.scrollHeight;

            // Log the updated scroll value
            console.log('Updated top:', wrapper.scrollTop);
        };


        window.addEventListener('load', scrollBottomWrapper);

    </script>
@endpush
