@extends('backend.app')

@section('title', 'Edit Location Groups | ' . ($setting->title ?? 'Cazzle'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
    <style>
        .card-img-top {
            object-fit: cover;
            width: 100%;
            height: 200px;
            border-radius: 8px;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .image-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            margin-bottom: 15px;
        }

        .form-select {
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            margin-bottom: 15px;
        }



        .card-body {
            padding: 20px;
        }

        .card-body h6 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
        }

        /* Hidden File Input */
        .file-input {
            display: none;
        }
    </style>
@endpush

@section('content')
<main class="p-6">
    <h3 class="mb-4">Edit Location Group</h3>

    <!-- Location Group Name -->
    <form action="{{ route('group.update', $locationGroup->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="groupName" class="form-label">Location Group Name</label>
            <input type="text" id="groupName" name="name" class="form-control" value="{{ $locationGroup->name }}">
        </div>

        <!-- Images and Locations -->
        <h4 class="mb-3">Images with Associated Locations</h4>

        <div class="image-container">
            @foreach($locationGroup->images as $image)
                <div class="card">
                    <!-- Display old image, clickable to trigger file input -->
                    <img src="{{ asset($image->avatar) }}" class="card-img-top" alt="Location Image {{ $loop->index + 1 }}" onclick="document.getElementById('image_{{ $image->id }}').click();">

                    <!-- Hidden file input -->
                    <input type="file" name="images[{{ $image->id }}]" id="image_{{ $image->id }}" class="file-input" onchange="previewImage(this, '{{ $image->id }}')">

                    <div class="card-body">
                        <h6 class="card-title">Select Location</h6>

                        <!-- Dropdown for Location Selection -->
                        <select name="locations[{{ $image->id }}]" class="form-select">
                            <option value="">-- Select Location --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ $image->location_id == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary me-2">Save Changes</button>
            <a href="{{ route('group.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

</main>

<script>
    // Preview uploaded image
    function previewImage(input, imageId) {
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            const newImage = document.getElementById('preview_image_' + imageId);

            // Create new image element to display the preview
            const previewContainer = input.closest('.card');
            let previewImage = previewContainer.querySelector('.card-img-top');
            previewImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
</script>

@endsection
