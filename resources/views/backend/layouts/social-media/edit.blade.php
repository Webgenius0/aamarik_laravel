@extends('backend.app')

@section('content')
    <div class="container max-w-md mx-auto">
        <h1 class="text-center text-xl font-semibold text-gray-900 mt-2">Edit Social Media</h1>
        <form id="socialMediaForm" action="{{ route('social.media.update', ['id' => $socialMedia->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <input type="hidden" name="id" id="socialMediaId" value="{{ $socialMedia->id }}">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title:</label>
                    <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        name="title" id="title"
                        value="{{ old('title', $socialMedia->title) }}">
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-gray-700 font-medium mb-2">URL:</label>
                    <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        name="url" id="url"
                        value="{{ old('url', $socialMedia->url) }}">
                </div>
            </div>
            <!-- Footer -->
            <div class="flex justify-end space-x-2 p-4 border-t border-gray-200">
                <a href="{{ url()->previous() }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</a>
                <button id="fetch_news_data" type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
            </div>
        </form>
    </div>
@endsection
