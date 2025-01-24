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
            <textarea name="about[0][short_description ]" class="form-input w-full" placeholder="Description"></textarea>
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

        <!-- Container for dynamic question cards -->
        <div id="cards-container">
            <div class="dynamic-card">
                <!-- Question Input -->
                <div class="flex flex-col md:w-full">
                    <label for="assessments[0][question]" class="text-lg font-medium mb-2">Question</label>
                    <input name="assessments[0][question]" class="form-input w-full" placeholder="Question">
                </div>

                <!-- Option Inputs -->
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

                <!-- More Option Inputs and Answer -->
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
            } else if (step === 4) {
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
            if (currentStep < 4) {
                currentStep++;
                if (currentStep === 3) {
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

    //add Assestment card

    // JavaScript to handle dynamic card addition/removal
    document.getElementById('addBtn').addEventListener('click', function() {
        // Clone the first dynamic card
        var card = document.querySelector('.dynamic-card');
        var clone = card.cloneNode(true);

        // Clear the input values in the cloned card
        var inputs = clone.querySelectorAll('input, textarea');
        inputs.forEach(function(input) {
            input.value = '';
        });

        // Show the remove button for the new card
        var removeButton = clone.querySelector('.remove-btn');
        removeButton.classList.remove('hidden');

        // Append the cloned card to the cards container
        document.getElementById('cards-container').appendChild(clone);
    });

    // Event delegation for handling remove buttons in dynamically added cards
    document.getElementById('cards-container').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-btn')) {
            // Remove the card
            e.target.closest('.dynamic-card').remove();
        }
    });


    //Array Quesstion Answer
    document.addEventListener("DOMContentLoaded", function() {
    let faqIndex = 0;  // Start from 0 for the first card

    // Add event listener for adding new FAQ cards
    document.getElementById('add-array-question').addEventListener('click', function() {
        // Select the first card inside the container (only once)
        var cardContainer = document.getElementById('array-question-answer');
        var card = cardContainer.querySelector('.card');  // Make sure it selects the card template

        if (!card) {
            console.log("Card element not found!");
            return;  // If no card exists to clone, exit the function
        }

        var clone = card.cloneNode(true);  // Clone the first card inside the container

        // Clear the input values in the cloned card
        var inputs = clone.querySelectorAll('input, textarea');
        inputs.forEach(function(input) {
            input.value = ''; // Clear the input field in the cloned card
        });

        // Show the remove button for the new card
        var removeButton = clone.querySelector('.remove-array-question-btn');
        removeButton.classList.remove('hidden'); // Unhide the remove button for the cloned card

        // Update the name attributes with unique indexes
        var questionInput = clone.querySelector('input[name="faqs[][question]"]');
        var answerInput = clone.querySelector('textarea[name="faqs[][answer]"]');
        
        if (questionInput && answerInput) {
            questionInput.name = `faqs[${faqIndex}][question]`;
            answerInput.name = `faqs[${faqIndex}][answer]`;
        } else {
            console.error('Failed to find input fields to update the name attributes');
        }

        // Append the cloned card to the parent container
        cardContainer.appendChild(clone);

        // Increment the faqIndex for the next card
        faqIndex++;

        // Optional: Add functionality to remove the cloned card when the remove button is clicked
        removeButton.addEventListener('click', function() {
            clone.remove();
        });
    });
});



    //for submission question answer

    

    //Araay Title 

    const addButton = document.getElementById('add-title-field');
    const removeButton = document.getElementById('remove-title-field');
    const arrayTitleContainer = document.getElementById('array-title-container');

    // Track the number of fields added
    let fieldCount = 1; // We start with 1 field already present

    // Handle the addition of new title fields
    addButton.addEventListener('click', function() {
        // Increment the field count
        fieldCount++;

        // Create a new title field element
        const newTitleField = document.createElement('div');
        newTitleField.classList.add('flex', 'flex-col', 'md:w-full');

        newTitleField.innerHTML = `
        <label for="description" class="text-lg font-medium mb-2">Title</label>
        <textarea name="title[]" class="form-input w-full" id="title" placeholder="title"></textarea>
    `;

        // Append the new title field to the container
        arrayTitleContainer.insertBefore(newTitleField, removeButton.parentNode); // Insert before the buttons

        // Show the remove button
        removeButton.classList.remove('hidden');
    });

    // Handle the removal of the last title field
    removeButton.addEventListener('click', function() {
        // Only remove if there are more than 1 field
        if (fieldCount > 1) {
            // Remove the last title field
            const titleFields = arrayTitleContainer.querySelectorAll('.flex.flex-col.md\\:w-full');
            const lastTitleField = titleFields[titleFields.length - 1];
            lastTitleField.remove();

            // Decrement the field count
            fieldCount--;

            // Hide the remove button if no fields left to remove
            if (fieldCount === 1) {
                removeButton.classList.add('hidden');
            }
        }
    });

    //Add new treatment category
    // Event listener to add a new treatment category card when the 'Add New Card' button is clicked
    document.getElementById('addtreament_category').addEventListener('click', function() {
        // Select the original treatment category card (the first one in the container)
        var originalCard = document.querySelector('#array-steps-container .treatment-category-card');

        // Clone the original card
        var clonedCard = originalCard.cloneNode(true);

        // Clear the input values for the cloned card (so they are empty for new inputs)
        var inputs = clonedCard.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.value = ''; // Clear the input and file fields
        });

        // Remove the hidden class from the Remove button in the cloned card
        var removeButton = clonedCard.querySelector('.remove-treament-category-btn');
        removeButton.classList.remove('hidden');

        // Append the cloned card to the container
        document.getElementById('array-steps-container').appendChild(clonedCard);

        // Add event listener for the Remove button in the new cloned card
        removeButton.addEventListener('click', function() {
            clonedCard.remove(); // Remove the respective cloned card from the DOM
        });
    });

    //form submission
//     $(document).ready(function () {
//     // Get CSRF token from meta tag
//     var token = $('meta[name="csrf-token"]').attr('content');

//     // Set the CSRF token globally for all AJAX requests
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': token
//         }
//     });

//     // Submit the form using AJAX
//     $('#createUpdateForm').submit(function (e) {
//         e.preventDefault(); // Prevent the default form submission

//         var formData = new FormData(this); // Grab the form data

//         $.ajax({
//             url: "{{ route('treatment.store') }}",  // Replace with your actual route
//             type: 'POST',
//             data: formData,
//             contentType: false,  // Prevent jQuery from setting content type
//             processData: false,  // Prevent jQuery from serializing the data
//             success: function (response) {
//                 if (response.success) {
//                     alert('Data submitted successfully!');
//                 } else {
//                     alert('There was an error with the submission');
//                 }
//             },
//             error: function (xhr, status, error) {
//                 alert('Error: ' + error);
//             }
//         });
//     });
// });

</script>
@endpush