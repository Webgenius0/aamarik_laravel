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
        /* Add this line to limit the height */
        overflow-y: auto;
        /* Allow scrolling if content overflows */
        padding: 20px;
        /* Optional: Adjust padding as needed */
    }

    #modalOverlay.modal-open #modal {
        opacity: 1;
        top: 50%;
    }

    #modal .modal-content {
        max-height: 70vh;
        /* Optional: Control the content area's max height */
        overflow-y: auto;
        /* Ensure content scrolls if it exceeds the height */
    }
</style>
@endpush

@section('content')
<main class="p-6">
    <div class="card bg-white overflow-hidden">
        <div class="card-header">
            <div class="flex justify-between align-middle">
                <h3 class="card-title">Treatment List</h3>
                <div>
                    <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                    <a href="{{route('treatment.index')}}">Create Treatment</a>
                    </button>

                </div>
            </div>
        </div>

        <div class="p-4">
    <div class="overflow-x-auto custom-scroll">
        <div class="min-w-full inline-block align-middle">
            <div class="overflow-hidden">
                <table id="data-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                S/L
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Avatar
                            </th>

                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div>
</main>


@endsection


@push('scripts')
<!-- Tailwind CSS CDN -->
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
{{-- Dropify --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
{{--Flashar--}}
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
{{-- Ck Editor --}}
<script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });


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
        var searchable = [];
        var selectable = [];
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }
        });
        $('#data-table').DataTable({
            order: [],
            lengthMenu: [
                [25, 50, 100, 200, 500, -1],
                [25, 50, 100, 200, 500, "All"]
            ],
            processing: true,
            responsive: true,
            serverSide: true,
            language: {
                processing: '<i class="ace-icon d-none fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
            },
            pagingType: "full_numbers",
            dom: "<'flex justify-between items-center mb-3'<'w-full sm:w-auto'l><'w-full text-center sm:w-auto'B><'w-full sm:w-auto'f>>tipr",
            ajax: {
                url: "{{ route('treatment.list') }}",
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'avatar',
                    name: 'avatar'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });





    // Add new input field when "+" is clicked
    document.getElementById('incrementBtn').addEventListener('click', function() {
        var container = document.getElementById('inputContainer');

        // Create a new input field container
        var newInputContainer = document.createElement('div');
        newInputContainer.classList.add('flex', 'items-center', 'mb-2');

        // Create a new input field
        var newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'feature[]';  // Allow multiple values to be submitted as an array
        newInput.classList.add('form-input', 'w-full');
        newInput.placeholder = 'Add feature';

        // Create a new remove button
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('ml-2', 'text-xl', 'font-semibold', 'removeBtn');
        removeButton.innerText = '-';  // Set text to "-"

        // Add event listener to remove input field when "-" is clicked
        removeButton.addEventListener('click', function() {
            newInputContainer.remove();
        });

        // Append the new input field and remove button to the container
        newInputContainer.appendChild(newInput);
        newInputContainer.appendChild(removeButton);

        // Append the new input container to the main container
        container.appendChild(newInputContainer);
    });

    // Show/hide remove button dynamically based on the presence of input fields
    document.getElementById('inputContainer').addEventListener('click', function(event) {
        if (event.target.tagName.toLowerCase() === 'input') {
            var removeBtns = document.querySelectorAll('.removeBtn');
            removeBtns.forEach(function(btn) {
                btn.classList.remove('hidden'); // Show the remove button
            });
        }
    });



    // delete Confirm
    function showDeleteConfirm(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to delete this record?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                deleteItem(id);
            }
        });
    };


    // Delete Button
    function deleteItem(id) {
        var url = '{{route('medicine.destroy', ':id')}}';
        $.ajax({
            type: "DELETE",
            url: url.replace(':id', id),
            success: function(resp) {
                console.log(resp);
                // Reloade DataTable
                $('#data-table').DataTable().ajax.reload();
                if (resp.success === true) {
                    // show toast message
                    flasher.success(resp.message);

                } else if (resp.errors) {
                    flasher.error(resp.errors[0]);
                } else {
                    flasher.error(resp.message);
                }
            }, // success end
            error: function(error) {
                // location.reload();
            } // Error
        })
    }





</script>
@endpush
