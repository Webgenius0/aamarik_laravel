@extends('backend.app')

@section('title', 'Edit Treatment | ' . ($data->name ?? 'PrimeCare'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <main class="p-6">
        <form id="editTreatmentForm" class="max-w-6xl w-full mx-auto space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $data->id }}">

            <!-- Step 1: Title and Avatar -->
            <div class="step step-1">
                <h1 class="text-2xl font-bold text-center mb-4">Edit Treatment</h1>
                <div class="mb-4">
                    <label class="text-lg font-medium">Title</label>
                    <input name="name" type="text" class="form-input w-full" value="{{ $data->name }}">
                </div>
                <div class="mb-4">
                    <label class="text-lg font-medium">Avatar</label>
                    <input name="avatar" type="file" class="dropify"
                           data-default-file="{{ asset($data->avatar) }}" accept="image/*">
                </div>
            </div>


            <!-- Step 2: Treatment Categories -->
            <div class="step step-2 hidden">
                <h2 class="text-xl font-semibold text-center mb-4">Treatment Categories</h2>
                <div id="category-container">
                    @foreach($data->categories as $index => $category)
                        <div class="card mb-4 p-4 border rounded-lg category-card">
                            <input type="hidden" name="categories[{{ $index }}][id]" value="{{ $category->id }}">
                            <label class="font-semibold">Icon</label>
                            <input name="categories[{{ $index }}][icon]" type="file" class="dropify"
                                   data-default-file="{{ asset($category->icon) }}" accept="image/*">
                            <label class="font-semibold mt-2">Title</label>
                            <input name="categories[{{ $index }}][title]" type="text" class="form-input w-full" value="{{ $category->title }}">
                            <button type="button" class="mt-2 bg-red-500 text-white px-3 py-1 rounded remove-category-btn">Remove</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-category-btn" class="mt-2 bg-green-500 text-white px-4 py-2 rounded">Add Category</button>
            </div>


{{--            <!-- Step 3: Treatment Details -->--}}
{{--            <div class="step step-3 hidden">--}}
{{--                <h2 class="text-xl font-semibold text-center mb-4">Treatment Details</h2>--}}
{{--                <div id="details-container">--}}
{{--                    @foreach($data->detail as $index => $detail)--}}
{{--                        <div class="card mb-4 p-4 border rounded-lg detail-card">--}}
{{--                            <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">--}}
{{--                            <label class="font-semibold">Avatar</label>--}}
{{--                            <input name="details[{{ $index }}][avatar]" type="file" class="dropify"--}}
{{--                                   data-default-file="{{ asset($detail->avatar) }}" accept="image/*">--}}
{{--                            <label class="font-semibold mt-2">Title</label>--}}
{{--                            <textarea name="details[{{ $index }}][title]" class="form-input w-full">{{ $detail->title }}</textarea>--}}
{{--                            <button type="button" class="mt-2 bg-red-500 text-white px-3 py-1 rounded remove-detail-btn">Remove</button>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--                <button type="button" id="add-detail-btn" class="mt-2 bg-green-500 text-white px-4 py-2 rounded">Add Detail</button>--}}
{{--            </div>--}}

            <!-- Step 4: Medicines -->
            <div class="step step-4 hidden">
                <h2 class="text-xl font-semibold text-center mb-4">Select Medicines</h2>
                <select name="medicines[]" id="medicines" class="form-input w-full" multiple>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}" @if($data->medicines->contains($medicine->id)) selected @endif>
                            {{ $medicine->title . ' - ' . $medicine->details->dosage }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-4">
                <button type="button" id="prevBtn" class="btn bg-gray-500 text-white px-4 py-2 rounded hidden">Previous</button>
                <button type="button" id="nextBtn" class="btn bg-blue-500 text-white px-4 py-2 rounded">Next</button>
                <button type="submit" id="submitBtn" class="btn bg-green-500 text-white px-4 py-2 rounded hidden">Update</button>
            </div>
        </form>
    </main>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
            $('#medicines').select2();

            let currentStep = 1;

            function showStep(step) {
                $('.step').addClass('hidden');
                $('.step-' + step).removeClass('hidden');

                if (step === 1) {
                    $('#prevBtn').addClass('hidden');
                    $('#nextBtn').removeClass('hidden');
                    $('#submitBtn').addClass('hidden');
                } else if (step === 4) {
                    $('#prevBtn').removeClass('hidden');
                    $('#nextBtn').addClass('hidden');
                    $('#submitBtn').removeClass('hidden');
                } else {
                    $('#prevBtn').removeClass('hidden');
                    $('#nextBtn').removeClass('hidden');
                    $('#submitBtn').addClass('hidden');
                }
            }

            $('#nextBtn').click(() => {
                if (currentStep < 4) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('#prevBtn').click(() => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });


            $(document).ready(function () {
                let categoryIndex = {{ count($data->categories) }}; // Start with existing categories count

                // Function to add a new category dynamically
                $('#add-category-btn').click(function () {
                    let newCategory = `
                    <div class="card mb-4 p-4 border rounded-lg category-card">
                        <input type="hidden" name="categories[${categoryIndex}][id]" value="">
                        <label class="font-semibold">Icon</label>
                        <input name="categories[${categoryIndex}][icon]" type="file" class="dropify"
                               accept="image/*">
                        <label class="font-semibold mt-2">Title</label>
                        <input name="categories[${categoryIndex}][title]" type="text" class="form-input w-full">
                        <button type="button" class="mt-2 bg-red-500 text-white px-3 py-1 rounded remove-category-btn">Remove</button>
                    </div>`;

                    $('#category-container').append(newCategory);
                    $('.dropify').dropify(); // Reinitialize Dropify
                    categoryIndex++; // Increment index
                });

                // Function to remove a category dynamically
                $(document).on('click', '.remove-category-btn', function () {
                    $(this).closest('.category-card').remove();
                });
            });


            $('#editTreatmentForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('treatment.update', $data->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert("Treatment Updated Successfully");
                        window.location.href = response.redirect;
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            showStep(currentStep);
        });
    </script>
@endpush
