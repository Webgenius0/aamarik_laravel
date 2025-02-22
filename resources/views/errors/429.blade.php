@extends('errors.minimal')

@section('title', '429 Too Many Requests')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-800">
        <div class="text-center">
            <h2 class="text-5xl font-bold text-white mb-4">429<span class="text-lg"> Too Many Requests</span></h2>
            <h5 class="text-lg text-white mb-6">Oops! You have sent too many requests in a short period. Please try again later.</h5>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    <i class="fa fa-long-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
