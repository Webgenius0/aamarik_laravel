@extends('backend.app')

@section('content')
    <div class="container max-w-md mx-auto">
        <h1 class="text-center text-xl font-semibold text-gray-900 mt-2">Create Social Media</h1>
        <form id="socialMediaForm" action="{{ route('social.media.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title:</label>
                    <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm md:w-1/2"
                        name="title" id="title" placeholder="Facebook, Twitter, Instagram, etc."
                        value="{{ old('title') }}">
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-gray-700 font-medium mb-2">URL:</label>
                    <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm md:w-1/2"
                        name="url" id="url" placeholder="https://example.com"
                        value="{{ old('url') }}">
                </div>
                <!-- Hidden field for status -->
                <input type="hidden" name="status" value="1">
            </div>
            <!-- Footer -->
            <div class="flex justify-end space-x-2 p-4 border-t border-gray-200">
                <a href="{{ url()->previous() }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</a>
                <button id="fetch_news_data" type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create</button>
            </div>
        </form>
    </div>
@endsection
