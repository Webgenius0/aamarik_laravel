@extends('errors.minimal')

@section('title', '401 Unauthorized')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-800">
        <div class="text-center">
            <h2 class="text-5xl font-bold text-white mb-4">401<span class="text-lg">Unauthorized</span></h2>
            <h5 class="text-lg text-white mb-6">                      Oops! You are not authorized to access this page.</h5>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    <i class="fa fa-long-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
