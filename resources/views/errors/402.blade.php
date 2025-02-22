@extends('errors.minimal')

@section('title', '402 Payment Required')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-800">
        <div class="text-center">
            <h2 class="text-5xl font-bold text-white mb-4">402<span class="text-lg"> Payment Required</span></h2>
            <h5 class="text-lg text-white mb-6">  Oops! Payment is required to access this resource.</h5>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    <i class="fa fa-long-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
