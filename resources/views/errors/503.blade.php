@extends('errors.minimal')

@section('title', '503 Service Unavailable')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-800">
        <div class="text-center">
            <h2 class="text-5xl font-bold text-white mb-4">503<span class="text-lg"> Service Unavailable</span></h2>
            <h5 class="text-lg text-white mb-6">Oops! The service is temporarily unavailable. Please try again later.</h5>
            <div class="mt-6">
                <a href="{{ route('welcome') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    <i class="fa fa-long-arrow-left mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
