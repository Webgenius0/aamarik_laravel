@extends('backend.app')

@section('title', 'Treatment| ' . $setting->title ?? 'PrimeCare')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
<!-- Dropify CSS for file input styling -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<!-- Tailwind CSS for layout and styling -->
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_length select {
        width: 68px;
        font-size: 14px;
        border: 0;
        border-radius: 0px;
        padding: 5px;
        background-color: transparent;
        padding: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: rgb(60 186 222 / var(--tw-bg-opacity));
        color: white !important;
        border: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: rgb(32 183 153 / var(--tw-bg-opacity));
        color: white !important;
        border: none;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 11px;
    }

    td,
    td p {
        font-size: 15px;
    }

    #data-table {
        border: 0;
        margin-bottom: 24px;
    }

    div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm) {
        background-color: #7066e0 !important;
    }

    div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-cancel) {
        background-color: #6e7881 !important;
    }

    #modalOverlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 9999;
    }

    #modal {
        position: fixed;
        width: 90%;
        top: 55%;
        left: 50%;
        text-align: center;
        background-color: #fafafa;
        box-sizing: border-box;
        opacity: 0;
        transform: translate(-50%, -50%);
        transition: all 300ms ease-in-out;
        max-height: 80%;
        overflow-y: auto;
        padding: 20px;
    }

    #modalOverlay.modal-open #modal {
        opacity: 1;
        top: 50%;
    }

    #modal .modal-content {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<main class="p-6">
</main>

<!-- Form Start -->
<form id="createUpdateForm" class="max-w-6xl w-full mx-auto space-y-4" enctype="multipart/form-data">

    <input type="hidden" name="id" id="medicine_id">

    <!-- Step 1: Title and Avatar -->
    <div class="step step-1">
        <div class="flex items-center justify-center">
            <h1 class="h1">Create Treatment</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="name" class="text-lg font-medium mb-2">Title</label>
            <input name="name" type="text" class="form-input w-full" id="name" placeholder="Enter name..">
        </div>

        <div class="flex flex-col md:w-full">
            <label for="avatar" class="text-lg font-medium mb-2">Avatar</label>
            <input name="avatar" type="file" class="form-input w-full dropify" id="avatar" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
    </div>

    <!-- Step 2: Treatment Category -->
    <div class="step step-2 hidden" id="treament_category">
        <div id="array-steps-container">
            <!-- Initial Card -->
            <div class="card m-5 p-5 treatment-category-card">
                <div class="flex items-center justify-center">
                    <h1 class="h1">Treatment Category</h1>
                </div>
                <div class="flex flex-wrap md:flex-nowrap">
                    <div class="flex flex-col md:w-1/2 mr-4">
                        <label for="categories[][icon]" class="text-lg font-medium mb-2">Icon</label>
                        <input name="categories[][icon]" type="file" class="form-input w-full dropify h-[2.5rem]" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="categories[][title]" class="text-lg font-medium mb-2">Title</label>
                        <input name="categories[][title]" type="text" class="form-input w-full h-[2.5rem]" placeholder="Title">
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-treament-category-btn hidden">Remove</button>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" id="addtreament_category" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">Add New Card</button>
        </div>
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
                    <input name="details[0][avatar]" type="file" class="form-input w-full dropify" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
                </div>

                <div class="flex flex-col md:w-1/2">
                    <label for="details[0][title]" class="text-lg font-medium mb-2">Title</label>
                    <textarea name="details[0][title]" class="form-input w-full" placeholder="Title"></textarea>
                </div>
            </div>

            <div id="array-title-container" class="flex flex-wrap items-center">
                <div class="flex flex-col md:w-full mr-4" id="aray_title">
                    <label for="detail_items[][title]" class="text-lg font-medium mb-2">Title</label>
                    <textarea name="detail_items[][title]" class="form-input w-full" placeholder="Title"></textarea>
                </div>

                <div class="flex items-center space-x-4 mt-4">
                    <button type="button" id="add-title-field" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">+</button>
                    <button type="button" id="remove-title-field" class="btn bg-red-500 text-white py-2 px-4 rounded-lg font-semibold hidden">-</button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <h1 class="h1">About Section</h1>
        </div>
        <div class="card m-5 p-5">
            <div class="flex flex-col md:w-full">
                <label for="about[0][avatar]" class="text-lg font-medium mb-2">Avatar</label>
                <input name="about[0][avatar]" type="file" class="form-input w-full dropify" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            </div>
            <div class="flex flex-wrap md:flex-nowrap">
                <div class="flex flex-col md:w-1/2">
                    <label for="about[0][title]" class="text-lg font-medium mb-2">Title</label>
                    <input name="about[0][title]" class="form-input w-full" placeholder="Title">
                </div>
                <div class="flex flex-col md:w-1/2 ml-4">
                    <label for="about[0][short_description]" class="text-lg font-medium mb-2">Description</label>
                    <textarea name="about[0][short_description]" class="form-input w-full" placeholder="Description"></textarea>
                </div>
            </div>
        </div>

        <div id="array-question-answer">
            <div class="card m-5 p-5">
                <div class="flex flex-wrap md:flex-nowrap">
                    <div class="flex flex-col md:w-1/2">
                        <label for="faqs[0][question]" class="text-lg font-medium mb-2">Question</label>
                        <input name="faqs[0][question]" class="form-input w-full" placeholder="Question">
                    </div>

                    <div class="flex flex-col md:w-1/2 ml-4">
                        <label for="faqs[0][answer]" class="text-lg font-medium mb-2">Answer</label>
                        <textarea name="faqs[0][answer]" class="form-input w-full" placeholder="Answer"></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-array-question-btn hidden">Remove</button>
                </div>
            </div>
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
                <div class="dynamic-card">
                    <div class="flex flex-col md:w-full">
                        <label for="assessments[0][question]" class="text-lg font-medium mb-2">Question</label>
                        <input name="assessments[0][question]" class="form-input w-full" placeholder="Question">
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="flex flex-col md:w-1/2">
                            <label for="assessments[0][option1]" class="text-lg font-medium mb-2">Option 1</label>
                            <input name="assessments[0][option1]" class="form-input w-full" placeholder="Option 1">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="assessments[0][option2]" class="text-lg font-medium mb-2">Option 2</label>
                            <input name="assessments[0][option2]" class="form-input w-full" placeholder="Option 2">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="assessments[0][option3]" class="text-lg font-medium mb-2">Option 3</label>
                            <input name="assessments[0][option3]" class="form-input w-full" placeholder="Option 3">
                        </div>
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="flex flex-col md:w-1/2">
                            <label for="assessments[0][option4]" class="text-lg font-medium mb-2">Option 4</label>
                            <input name="assessments[0][option4]" class="form-input w-full" placeholder="Option 4">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="assessments[0][answer]" class="text-lg font-medium mb-2">Answer</label>
                            <input name="assessments[0][answer]" class="form-input w-full" placeholder="Answer">
                        </div>
                        <div class="flex flex-col md:w-1/2 ml-4">
                            <label for="assessments[0][note]" class="text-lg font-medium mb-2">Note</label>
                            <textarea name="assessments[0][note]" class="form-input w-full" placeholder="Note"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-btn hidden">Remove</button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" id="addBtn" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold">Add New Card</button>
            </div>
        </div>
    </div>


    <!-- Step 5: Treatment Medicine -->
    <div class="step step-5 hidden">
        <div class="card m-5 p-5">
            <div class="flex items-center justify-center">
                <h1 class="h1">Assign Treatment Medicine</h1>
            </div>
            <div class="flex flex-col">
                <label for="medicines" class="text-lg font-medium mb-2">Select Medicines</label>
                <select name="medicines[]" id="medicines" class="form-input w-full select2" multiple="multiple">
                    <option value="medicine1">Medicine 1</option>
                    <option value="medicine2">Medicine 2</option>
                    <option value="medicine3">Medicine 3</option>
                    <option value="medicine4">Medicine 4</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-between mt-4">
        <button type="button" id="prevBtn" class="btn bg-gray-500 text-white py-2 px-4 rounded-lg font-semibold hidden">Previous</button>
        <button type="button" id="nextBtn" class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold">Next</button>
        <button type="submit" id="submitBtn" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold hidden">Submit</button>
    </div>
</form>





@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify({

        });

        let currentStep = 1; // Start from step 1

        // Show step based on currentStep
        function showStep(step) {
            $('.step').addClass('hidden');
            $('.step-' + step).removeClass('hidden');

            reinitializeDropify();

            // Show/hide Previous, Next, and Submit buttons based on current step
            if (step === 1) {
                $('#prevBtn').addClass('hidden');
                $('#nextBtn').removeClass('hidden');
                $('#submitBtn').addClass('hidden');
            } else if (step >= 2 && step <= 3) {
                $('#prevBtn').removeClass('hidden');
                $('#nextBtn').removeClass('hidden');
                $('#submitBtn').addClass('hidden');
            } else if (step === 5) {
                $('#prevBtn').removeClass('hidden');
                $('#nextBtn').addClass('hidden');
                $('#submitBtn').removeClass('hidden');
            }
        }

        // Function to reinitialize Dropify
        function reinitializeDropify() {
            // Reinitialize Dropify for file inputs in the visible step
            $('.step-' + currentStep + ' .dropify').dropify();
        }

        // Go to the next step
        // Go to the next step
        $('#nextBtn').click(function() {
            // Ensure we are not going beyond step 8
            if (currentStep < 5) {
                currentStep++;
                if (currentStep === 4) {
                    // If it's the final step, hide "Next" and show "Submit"
                    $('#nextBtn').addClass('hidden');
                    $('#submitBtn').removeClass('hidden');
                }
                showStep(currentStep);
            }
        });


        // Go to the previous step
        $('#prevBtn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Handle form submission
        $('#createUpdateForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var url = "{{ route('treatment.store') }}"; // Default route for creation

            var method = "POST"; // Default method for creation
            var token = $('meta[name="csrf-token"]').attr('content');

//     // Set the CSRF token globally for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            $.ajax({
                type: method,
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    // Handle success response
                    alert(resp.message);
                    $('#createUpdateForm')[0].reset();
                    showStep(1); // Reset to step 1
                },
                error: function(error) {
                    // Handle error
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Initialize the first step
        showStep(currentStep);
    });

    document.addEventListener('DOMContentLoaded', function() {
    let faqIndex = 1;
    let assessmentIndex = 1;
    let titleIndex = 1;

    // Add FAQ Card
    document.getElementById('add-array-question').addEventListener('click', function() {
        const faqContainer = document.getElementById('array-question-answer');
        const newCard = document.createElement('div');
        newCard.classList.add('card', 'm-5', 'p-5');
        newCard.innerHTML = `
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
        `;
        faqContainer.appendChild(newCard);
        faqIndex++;

        // Add event listener to "Remove" button
        newCard.querySelector('.remove-array-question-btn').addEventListener('click', function() {
            newCard.remove();
        });
    });

    // Add Assessment Card
    document.getElementById('addBtn').addEventListener('click', function() {
        const cardsContainer = document.getElementById('cards-container');
        const newCard = document.createElement('div');
        newCard.classList.add('dynamic-card', 'm-5', 'p-5');
        newCard.innerHTML = `
            <div class="flex flex-col md:w-full">
                <label for="assessments[${assessmentIndex}][question]" class="text-lg font-medium mb-2">Question</label>
                <input name="assessments[${assessmentIndex}][question]" class="form-input w-full" placeholder="Question">
            </div>

            <div class="flex flex-wrap md:flex-nowrap">
                <div class="flex flex-col md:w-1/2">
                    <label for="assessments[${assessmentIndex}][option1]" class="text-lg font-medium mb-2">Option 1</label>
                    <input name="assessments[${assessmentIndex}][option1]" class="form-input w-full" placeholder="Option 1">
                </div>
                <div class="flex flex-col md:w-1/2 ml-4">
                    <label for="assessments[${assessmentIndex}][option2]" class="text-lg font-medium mb-2">Option 2</label>
                    <input name="assessments[${assessmentIndex}][option2]" class="form-input w-full" placeholder="Option 2">
                </div>
                <div class="flex flex-col md:w-1/2 ml-4">
                    <label for="assessments[${assessmentIndex}][option3]" class="text-lg font-medium mb-2">Option 3</label>
                    <input name="assessments[${assessmentIndex}][option3]" class="form-input w-full" placeholder="Option 3">
                </div>
            </div>

            <div class="flex flex-wrap md:flex-nowrap">
                <div class="flex flex-col md:w-1/2">
                    <label for="assessments[${assessmentIndex}][option4]" class="text-lg font-medium mb-2">Option 4</label>
                    <input name="assessments[${assessmentIndex}][option4]" class="form-input w-full" placeholder="Option 4">
                </div>
                <div class="flex flex-col md:w-1/2 ml-4">
                    <label for="assessments[${assessmentIndex}][answer]" class="text-lg font-medium mb-2">Answer</label>
                    <input name="assessments[${assessmentIndex}][answer]" class="form-input w-full" placeholder="Answer">
                </div>
                <div class="flex flex-col md:w-1/2 ml-4">
                    <label for="assessments[${assessmentIndex}][note]" class="text-lg font-medium mb-2">Note</label>
                    <textarea name="assessments[${assessmentIndex}][note]" class="form-input w-full" placeholder="Note"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-btn">Remove</button>
            </div>
        `;
        cardsContainer.appendChild(newCard);
        assessmentIndex++;

        // Add event listener to "Remove" button
        newCard.querySelector('.remove-btn').addEventListener('click', function() {
            newCard.remove();
        });
    });



    //document.getElementById('addtreament_category').addEventListener('click', function() {
    const container = document.getElementById('array-steps-container');
    const newCard = document.createElement('div');
    newCard.classList.add('card', 'm-5', 'p-5', 'treatment-category-card');
    newCard.innerHTML = `
        <div class="flex items-center justify-center">
            <h1 class="h1">Treatment Category</h1>
        </div>
        <div class="flex flex-wrap md:flex-nowrap">
            <div class="flex flex-col md:w-1/2 mr-4">
                <label for="categories[][icon]" class="text-lg font-medium mb-2">Icon</label>
                <input name="categories[][icon]" type="file" class="form-input w-full dropify h-[2.5rem]" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            </div>
            <div class="flex flex-col md:w-1/2">
                <label for="categories[][title]" class="text-lg font-medium mb-2">Title</label>
                <input name="categories[][title]" type="text" class="form-input w-full h-[2.5rem]" placeholder="Title">
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" class="btn bg-red-500 text-white py-1 px-3 rounded-lg font-semibold remove-treament-category-btn">Remove</button>
        </div>
    `;
    container.appendChild(newCard);

    // Attach event listener to the remove button inside the new card
    newCard.querySelector('.remove-treament-category-btn').addEventListener('click', function() {
        newCard.remove();
    });
});


document.getElementById('add-title-field').addEventListener('click', function() {
    const container = document.getElementById('array-title-container');

    // Create a new title field
    const newTitleField = document.createElement('div');
    newTitleField.classList.add('flex', 'flex-col', 'md:w-full', 'mr-4');
    newTitleField.innerHTML = `
        <label for="detail_items[][title]" class="text-lg font-medium mb-2">Title</label>
        <textarea name="detail_items[][title]" class="form-input w-full" placeholder="Title"></textarea>
    `;

    // Append the new title field to the container
    container.insertBefore(newTitleField, container.querySelector('.flex.items-center'));

    // Show the remove button when a new field is added
    document.getElementById('remove-title-field').classList.remove('hidden');
});

// Add event listener for the remove button
document.getElementById('remove-title-field').addEventListener('click', function() {
    const container = document.getElementById('array-title-container');
    const titleFields = container.querySelectorAll('.flex.flex-col');

    // If there is more than one title field, remove the last one
    if (titleFields.length > 1) {
        titleFields[titleFields.length - 1].remove();
    }

    // If there is only one field left, hide the remove button
    if (titleFields.length === 1) {
        document.getElementById('remove-title-field').classList.add('hidden');
    }
});






</script>
@endpush
