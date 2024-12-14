@extends('backend.app')

@section('title', 'List Of Location Groups | ' . $setting->title ?? 'Cazzle')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
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


        /* images  grid  */

        .file-slot {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .file-slot img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="grid lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2">
                <div class="card bg-white overflow-hidden">
                    <div class="card-header">
                        <div class="flex justify-between align-middle">
                            <h3 class="card-title">List Of Location Groups</h3>
                        </div>
                    </div>



                    <!-- Modal for Editing Location Group -->
                    <!-- Modal for Editing Location Group -->
                    <div id="editLocationGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                        <div class="flex items-center justify-center min-h-screen">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative">
                                <!-- Modal Header with Title and Close Button -->
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold">Edit Location Group</h3>
                                    <!-- Close Button -->
                                    <button type="button" class="text-black text-2xl font-bold"
                                        id="closeModalBtn">&times;</button>
                                </div>

                                <form id="edit-location-group-form" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Group Name -->
                                    <div class="mb-4">
                                        <label for="edit-name" class="block text-sm font-medium text-gray-700">Group
                                            Name</label>
                                        <input type="text" id="edit-name" name="name"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    </div>

                                    <!-- Location Selector -->
                                    <div class="mb-4">
                                        <label for="edit-location"
                                            class="block text-sm font-medium text-gray-700">Location</label>
                                        <select id="edit-location" name="location_id"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                            <!-- Dynamic locations will be populated here -->
                                        </select>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Images</label>
                                        <div id="image-preview-container" class="grid grid-cols-3 gap-3">
                                            <!-- Images will be dynamically inserted here -->
                                        </div>
                                        <input type="file" id="edit-images" name="images[]" accept="image/*" multiple
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="mt-6">
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                                            Update Location Group
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for Editing Location Group End -->

                    <!--Modal for Editing Location Group end-->



                    <div class="p-4">
                        <div class="overflow-x-auto custom-scroll">
                            <div class="min-w-full inline-block align-middle p-2">
                                <div class="overflow-hidden">
                                    <table id="data-table" class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    S/L
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Location Name
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Group Name
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Dynamic content goes here -->
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="p-4">
                    <!-- Puzzle Upload Form -->
                    <form action="{{ route('group.store') }}" id="puzzle-form" method="POST" enctype="multipart/form-data"
                        class="bg-white p-6 rounded shadow">
                        @csrf
                        <!-- Group Name Input -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Group Name</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Locations selector Dropdown -->
                        <div class="mb-4">
                            <label for="sector" class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="sector" name="location_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Select a Location</option>
                                @forelse ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->title }}</option>
                                @empty
                                    <option value="">No locations available</option>
                                @endforelse
                            </select>
                            @error('location_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Slots -->
                        <div class="grid grid-cols-3 gap-3">
                            @for ($i = 0; $i < 9; $i++)
                                <label for="file-input-{{ $i }}">
                                    <div id="slot-{{ $i }}" class="file-slot"></div>
                                    <input type="file" id="file-input-{{ $i }}" name="images[]"
                                        onchange="previewImage({{ $i }})" class="hidden" accept="image/*">
                                </label>
                            @endfor
                            @error('images')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>



@endsection


@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>


    <script>
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
                    url: "{{ route('group.index') }}",
                    type: "GET",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'location_name',
                        name: 'location_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'group_name',
                        name: 'group_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });


        // Open modal and populate form
        // $(document).on('click', '.edit-location-group-btn', function() {
        //     let groupId = $(this).data('id');

        //     // Show the modal
        //     $('#editLocationGroupModal').removeClass('hidden');

        //     // Make an AJAX request to fetch location group data
        //     const url = "{{ route('group.show', ['id' => ':id']) }}".replace(':id', groupId);

        //     $.ajax({
        //         url: url,
        //         method: 'GET',
        //         success: function(response) {
        //             $('#edit-location-group-form').attr('action', '/location-group/' + groupId);
        //             $('#edit-name').val(response.locationGroup.name);
        //             $('#edit-location').val(response.locationGroup.location_id);

        //             // Populate location select options dynamically
        //             let locations = response.locations;
        //             $('#edit-location').empty();
        //             locations.forEach(function(location) {
        //                 $('#edit-location').append(new Option(location.title, location.id));
        //             });
        //         }
        //     });
        // });


        // JavaScript for closing the modal
        // $(document).ready(function() {
        //     // Close modal when '×' button is clicked
        //     $('#closeModalBtn').on('click', function() {
        //         $('#editLocationGroupModal').addClass('hidden'); // Hide modal
        //     });

        //     // Optional: Close modal when clicking outside of the modal content area
        //     $('#editLocationGroupModal').on('click', function(event) {
        //         if ($(event.target).is('#editLocationGroupModal')) {
        //             $('#editLocationGroupModal').addClass('hidden');
        //         }
        //     });
        // });



        $(document).on('click', '.edit-location-group-btn', function() {
            let groupId = $(this).data('id');

            // Show the modal
            $('#editLocationGroupModal').removeClass('hidden');

            // Make an AJAX request to fetch location group data
            const url = "{{ route('group.show', ['id' => ':id']) }}".replace(':id', groupId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    // Populate the form with fetched data
                    $('#edit-location-group-form').attr('action', '{{ route('group.update', ':id') }}'.replace(':id', groupId));
                    $('#edit-name').val(response.locationGroup.name);
                    $('#edit-location').val(response.locationGroup.location_id);

                    // Populate location select options dynamically
                    let locations = response.locations;
                    $('#edit-location').empty();
                    locations.forEach(function(location) {
                        $('#edit-location').append(new Option(location.title, location.id));
                    });

                    // Show image previews if available
                    let images = response.locationGroup.images;
                    $('#image-preview-container').empty(); // Clear any existing images
                    if (images.length > 0) {
                        images.forEach(function(image) {
                            let imgPreview = `<div class="file-slot">
                                <img src="${image}" alt="Image preview" class="w-24 h-24 object-cover"/>
                            </div>`;
                            $('#image-preview-container').append(imgPreview);
                        });
                    }
                },
                error: function() {
                    alert('Failed to fetch location group data.');
                }
            });
        });

        // Close Modal when clicking on the close button
        $('#closeModalBtn').on('click', function() {
            $('#editLocationGroupModal').addClass('hidden');
        });

        // Optional: Close modal when clicking outside of the modal content
        $('#editLocationGroupModal').on('click', function(event) {
            if ($(event.target).is('#editLocationGroupModal')) {
                $('#editLocationGroupModal').addClass('hidden');
            }
        });






        // Preview Image Function
        function previewImage(slot) {
            const fileInput = document.getElementById(`file-input-${slot}`);
            const slotDiv = document.getElementById(`slot-${slot}`);

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Display the uploaded image in the slot
                    slotDiv.innerHTML = ''; // Clear placeholder text
                    slotDiv.style.backgroundImage = `url('${e.target.result}')`;
                    slotDiv.style.backgroundSize = 'cover';
                    slotDiv.style.backgroundPosition = 'center';
                    slotDiv.style.border = 'none';
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>
@endpush
