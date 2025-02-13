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


            <!-- Step 3: Category Details -->
            <div class="step step-3 hidden">
                <div class="flex items-center justify-center">
                    <h1 class="h1">Category Details</h1>
                </div>
                <div class="card m-5 p-5">
                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="flex flex-col md:w-1/2 mr-4">
                            <label for="details[0][avatar]" class="text-lg font-medium mb-2">Avatar</label>
                            <input name="details[0][avatar]" type="file" class="form-input w-full dropify" data-default-file="{{ asset($data->detail->avatar) }}" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
                        </div>

                        <div class="flex flex-col md:w-1/2">
                            <label for="details[0][title]" class="text-lg font-medium mb-2">Title</label>
                            <textarea name="details[0][title]" class="form-input w-full" placeholder="Title">{{ $data->detail->title  }}</textarea>
                        </div>
                    </div>

                    <div id="array-title-container" class="flex flex-wrap items-center">
                        @foreach($data->detailItems as $itemIndex => $item)
                            <div class="flex flex-col md:w-full mr-4 detail-item" data-id="{{ $item->id }}">
                                <input type="hidden" name="detail_items[{{ $itemIndex }}][id]" value="{{ $item->id }}">
                                <label class="text-lg font-medium mb-2">Title</label>
                                <textarea name="detail_items[{{ $itemIndex }}][title]" class="form-input w-full" placeholder="Title">{{ $item->title }}</textarea>
                                <button type="button" class="mt-2 bg-red-500 text-white px-3 py-1 rounded remove-title-field" data-id="{{ $item->id }}">Remove</button>
                            </div>
                        @endforeach

                        <div class="flex items-center space-x-4 mt-4">
                            <button type="button" id="add-title-field" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">+</button>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-center">
                    <h1 class="h1">About Section</h1>
                </div>
                <div class="card m-5 p-5">
                    <div class="flex flex-col md:w-full">
                        <label for="about[0][avatar]" class="text-lg font-medium mb-2">Avatar</label>
                        <input name="about[0][avatar]" type="file" class="form-input w-full dropify" data-default-file="{{ asset($data->about->avatar) }}" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
                    </div>
                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="flex flex-col md:w-1/2">
                            <label for="about[0][title]" class="text-lg font-medium mb-2">Title</label>
                            <input name="about[0][title]" class="form-input w-full" placeholder="Title" value="{{ old('about[0][title]', $data->about->title) }}">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="about[0][short_description]" class="text-lg font-medium mb-2">Description</label>
                            <textarea name="about[0][short_description]" class="form-input w-full" placeholder="Description">{{ $data->about->short_description }}</textarea>
                        </div>
                    </div>
                </div>



                <div id="array-question-answer">
                    @foreach($data->faqs as $faqIndex => $faq)
                        <div class="card m-5 p-5 faq-item">
                            <div class="flex flex-wrap md:flex-nowrap">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="faqs[{{ $faqIndex }}][question]" class="text-lg font-medium mb-2">Question</label>
                                    <input name="faqs[{{ $faqIndex }}][question]" class="form-input w-full" placeholder="Question" value="{{ old("faqs.$faqIndex.question", $faq->question) }}">
                                </div>

                                <div class="flex flex-col md:w-1/2 ml-4">
                                    <label for="faqs[{{ $faqIndex }}][answer]" class="text-lg font-medium mb-2">Answer</label>
                                    <textarea name="faqs[{{ $faqIndex }}][answer]" class="form-input w-full" placeholder="Answer">{{ old("faqs.$faqIndex.answer", $faq->answer) }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-array-question-btn">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" id="add-array-question" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">Add New Card</button>
                </div>

            </div>


            <!-- Step 4: Assessment -->
            <div class="step step-4 hidden">
                <div class="card m-5 p-5">
                    <div class="flex items-center justify-center">
                        <h1 class="h1">Assessment</h1>
                    </div>

                    <div id="cards-container">
                        @foreach($data->assessments as $assessmentIndex => $assessment)
                            <div class="dynamic-card card m-5 p-5">
                                <input type="hidden" name="assessments[{{ $assessmentIndex }}][id]" value="{{ $assessment->id }}"> <!-- Empty ID for new items -->
                                <div class="flex flex-col md:w-full">
                                    <label for="assessments[{{ $assessmentIndex }}][question]" class="text-lg font-medium mb-2">Question</label>
                                    <input name="assessments[{{ $assessmentIndex }}][question]" class="form-input w-full" placeholder="Question" value="{{ old("assessments.$assessmentIndex.question", $assessment->question) }}">
                                </div>

                                <div class="flex flex-wrap md:flex-nowrap">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="assessments[{{ $assessmentIndex }}][option1]" class="text-lg font-medium mb-2">Option 1</label>
                                        <input name="assessments[{{ $assessmentIndex }}][option1]" class="form-input w-full" placeholder="Option 1" value="{{ old("assessments.$assessmentIndex.option1", $assessment->option1) }}">
                                    </div>
                                    <div class="flex flex-col md:w-1/2 ml-4">
                                        <label for="assessments[{{ $assessmentIndex }}][option2]" class="text-lg font-medium mb-2">Option 2</label>
                                        <input name="assessments[{{ $assessmentIndex }}][option2]" class="form-input w-full" placeholder="Option 2" value="{{ old("assessments.$assessmentIndex.option2", $assessment->option2) }}">
                                    </div>
                                    <div class="flex flex-col md:w-1/2 ml-4">
                                        <label for="assessments[{{ $assessmentIndex }}][option3]" class="text-lg font-medium mb-2">Option 3</label>
                                        <input name="assessments[{{ $assessmentIndex }}][option3]" class="form-input w-full" placeholder="Option 3" value="{{ old("assessments.$assessmentIndex.option3", $assessment->option3) }}">
                                    </div>
                                </div>

                                <div class="flex flex-wrap md:flex-nowrap">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="assessments[{{ $assessmentIndex }}][option4]" class="text-lg font-medium mb-2">Option 4</label>
                                        <input name="assessments[{{ $assessmentIndex }}][option4]" class="form-input w-full" placeholder="Option 4" value="{{ old("assessments.$assessmentIndex.option4", $assessment->option4) }}">
                                    </div>
                                    <div class="flex flex-col md:w-1/2 ml-4">
                                        <label for="assessments[{{ $assessmentIndex }}][answer]" class="text-lg font-medium mb-2">Answer</label>
                                        <input name="assessments[{{ $assessmentIndex }}][answer]" class="form-input w-full" placeholder="Answer" value="{{ old("assessments.$assessmentIndex.answer", $assessment->answer) }}">
                                    </div>
                                    <div class="flex flex-col md:w-1/2 ml-4">
                                        <label for="assessments[{{ $assessmentIndex }}][note]" class="text-lg font-medium mb-2">Note</label>
                                        <textarea name="assessments[{{ $assessmentIndex }}][note]" class="form-input w-full" placeholder="Note">{{ old("assessments.$assessmentIndex.note", $assessment->note) }}</textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-assessment-btn">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" id="add-assessment-btn" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">Add New Assessment</button>
                    </div>

                </div>
            </div>


            <!-- Step 5: Medicines -->
            <div class="step step-5 hidden">
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
    {{--Flashar--}}
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>

    <script>
        $(document).ready(function() {
            const flasher = new Flasher({
                selector: '[data-flasher]',
                duration: 3000,
                options: {
                    position: 'top-center',
                },
            });
        });
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
                } else if (step >= 2 && step <= 4) {
                    $('#prevBtn').removeClass('hidden');
                    $('#nextBtn').removeClass('hidden');
                    $('#submitBtn').addClass('hidden');
                } else if (step === 5) {
                    $('#prevBtn').removeClass('hidden');
                    $('#nextBtn').addClass('hidden');
                    $('#submitBtn').removeClass('hidden');
                }
            }

            $('#nextBtn').click(() => {
                if (currentStep < 5) {
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



            //JavaScript to Handle Adding/Removing detailItems Dynamically
            $(document).ready(function () {
                let itemIndex = {{ count($data->detailItems) }}; // Start from existing count
                let removedItems = []; // Store IDs of removed old items

                //    Add New Detail Item
                $('#add-title-field').click(function () {
                    let newItem = `
                    <div class="flex flex-col md:w-full mr-4 detail-item">
                        <input type="hidden" name="detail_items[${itemIndex}][id]" value=""> <!-- No ID for new -->
                        <label for="detail_items[${itemIndex}][title]" class="text-lg font-medium mb-2">Title</label>
                        <textarea name="detail_items[${itemIndex}][title]" class="form-input w-full" placeholder="Title"></textarea>
                        <button type="button" class="mt-2 bg-red-500 text-white px-3 py-1 rounded remove-title-field">Remove</button>
                    </div>`;

                    $('#array-title-container').append(newItem);
                    itemIndex++;
                });

                //    Remove Detail Item (Both Old & New)
                $(document).on('click', '.remove-title-field', function () {
                    let itemDiv = $(this).closest('.detail-item');
                    let itemId = itemDiv.find('input[name^="detail_items["][name$="[id]"]').val(); // Get ID

                    if (itemId) {
                        //    Store ID for removal if it's an old item
                        removedItems.push(itemId);
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'removed_detail_items[]',
                            value: itemId
                        }).appendTo('#array-title-container');
                    }

                    // Remove the field from UI
                    itemDiv.remove();
                });

            });


            // JavaScript to Handle Adding & Removing FAQs Dynamically
            $(document).ready(function () {
                let faqIndex = {{ count($data->faqs) }}; // Start with existing count

                // Add new FAQ
                $('#add-array-question').click(function () {
                    let newFaq = `
                <div class="card m-5 p-5 faq-item">
                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="flex flex-col md:w-1/2">
                            <label for="faqs[${faqIndex}][question]" class="text-lg font-medium mb-2">Question</label>
                            <input name="faqs[${faqIndex}][question]" class="form-input w-full" placeholder="Question">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="faqs[${faqIndex}][answer]" class="text-lg font-medium mb-2">Answer</label>
                            <textarea name="faqs[${faqIndex}][answer]" class="form-input w-full" placeholder="Answer"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-array-question-btn">Remove</button>
                    </div>
                </div>`;

                    $('#array-question-answer').append(newFaq);
                    faqIndex++;
                });

                // Remove FAQ
                $(document).on('click', '.remove-array-question-btn', function () {
                    $(this).closest('.faq-item').remove();
                });
            });


            $(document).ready(function () {
                let assessmentIndex = {{ count($data->assessments) }}; // Start index from existing assessments

                //    Add New Assessment
                $('#add-assessment-btn').click(function () {
                    let newAssessment = `
                    <div class="dynamic-card card m-5 p-5">
                        <input type="hidden" name="assessments[${assessmentIndex}][id]" value=""> <!-- Empty ID for new items -->
                        <div class="flex flex-col md:w-full">
                            <label class="text-lg font-medium mb-2">Question</label>
                            <input name="assessments[${assessmentIndex}][question]" class="form-input w-full" placeholder="Question">
                        </div>

                        <div class="flex flex-wrap md:flex-nowrap">
                            <div class="flex flex-col md:w-1/2">
                                <label class="text-lg font-medium mb-2">Option 1</label>
                                <input name="assessments[${assessmentIndex}][option1]" class="form-input w-full" placeholder="Option 1">
                            </div>
                            <div class="flex flex-col md:w-1/2 ml-4">
                                <label class="text-lg font-medium mb-2">Option 2</label>
                                <input name="assessments[${assessmentIndex}][option2]" class="form-input w-full" placeholder="Option 2">
                            </div>
                            <div class="flex flex-col md:w-1/2 ml-4">
                                <label class="text-lg font-medium mb-2">Option 3</label>
                                <input name="assessments[${assessmentIndex}][option3]" class="form-input w-full" placeholder="Option 3">
                            </div>
                            <div class="flex flex-col md:w-1/2 ml-4">
                                <label class="text-lg font-medium mb-2">Option 4</label>
                                <input name="assessments[${assessmentIndex}][option4]" class="form-input w-full" placeholder="Option 4">
                            </div>
                        </div>

                        <div class="flex flex-wrap md:flex-nowrap">
                            <div class="flex flex-col md:w-1/2">
                                <label class="text-lg font-medium mb-2">Answer</label>
                                <input name="assessments[${assessmentIndex}][answer]" class="form-input w-full" placeholder="Answer">
                            </div>
                            <div class="flex flex-col md:w-1/2 ml-4">
                                <label class="text-lg font-medium mb-2">Note</label>
                                <textarea name="assessments[${assessmentIndex}][note]" class="form-input w-full" placeholder="Note"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-assessment-btn">Remove</button>
                        </div>
                    </div>`;

                    $('#cards-container').append(newAssessment);
                    assessmentIndex++;
                });

                //    Remove Assessment
                $(document).on('click', '.remove-assessment-btn', function () {
                    $(this).closest('.dynamic-card').remove();
                });
            });





            showStep(currentStep);
        });

        $(document).ready(function () {
            // Set CSRF token globally for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#editTreatmentForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('treatment.update', $data->id) }}",
                    type: "POST", // Laravel only allows POST for file uploads
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        flasher.success("Treatment Updated Successfully");

                        window.location.href = response.redirect;
                    },
                    error: function (error) {
                        console.log(error);
                        alert("Error updating treatment.");
                    }
                });
            });
        });

    </script>
@endpush
