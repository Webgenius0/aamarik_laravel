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
                    {{-- <div id="editLocationGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
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
                    </div> --}}
                    <!-- Modal for Editing Location Group End -->






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


            <!--create location group-->
            <div class="card">
                <div class="p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Puzzle Upload Form -->
                    <form class="bg-white space-y-4 p-6 rounded-lg shadow-md max-w-md w-full"
                        action="{{ route('group.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <label for="groupName" class="block text-sm font-medium text-gray-700">Group Name</label>
                            <input type="text" id="groupName" name="group_name" placeholder="Enter group name"
                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                required />
                        </fieldset>
                        <fieldset>
                            <label for="groupLocation" class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="groupLocation" name="groupLocation"
                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="" selected disabled>Select a Location</option>
                                @forelse ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->title }}</option>
                                @empty
                                    <option value="">No Locations Available</option>
                                @endforelse
                            </select>
                        </fieldset>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @for ($i = 0; $i < 9; $i++)
                                <div id="{{ $i }}"
                                    class="slot-box border-dashed border-2 border-gray-300 flex justify-center items-center h-24">
                                    <!-- slot box content start -->
                                    <div
                                        class="slot-box-content cursor-pointer w-full h-full flex items-center justify-center">
                                        <span class="text-gray-500 text-2xl">+</span>
                                    </div>
                                    <!-- slot box content end -->

                                    <!-- slot box modal start -->
                                    <div class="slot-box-modal fixed inset-0 z-50 flex items-center justify-center hidden">
                                        <div class="overlay bg-gray-800 bg-opacity-50 absolute inset-0 z-[-1]"></div>
                                        <div class="bg-white rounded-lg shadow-lg space-y-4 max-w-sm w-full p-6">
                                            <div class="text-lg font-semibold text-gray-800 mb-4">
                                                Upload Image and Add Text
                                            </div>
                                            <fieldset>
                                                <label class="block text-sm font-medium text-gray-700">Image</label>
                                                <input type="file" id="slot-image-{{ $i }}"
                                                    name="slotImages[]"
                                                    class="mt-1 block w-full border rounded-md p-2 border-gray-300"
                                                    accept="image/*" />
                                            </fieldset>
                                            <fieldset>
                                                <label class="block text-sm font-medium text-gray-700"
                                                    for="slot-location-{{ $i }}">Location</label>
                                                <select id="slot-location-{{ $i }}" name="slotLocation[]"
                                                    class="mt-1 block w-full border rounded-md p-2 border-gray-300">
                                                    <option value="" selected disabled>Select a Location</option>
                                                    @forelse ($locations as $location)
                                                        <option value="{{ $location->id }}">{{ $location->title }}</option>
                                                    @empty
                                                        <option value="">No Locations Available</option>
                                                    @endforelse
                                                </select>
                                            </fieldset>

                                            <!-- Buttons -->
                                            <div class="flex justify-end">
                                                <button type="button"
                                                    class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md mr-2">
                                                    Cancel
                                                </button>
                                                <button type="button"
                                                    class="open-modal-btn px-4 py-2 bg-blue-600 text-white rounded-md">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- slot box modal end -->
                                </div>
                            @endfor
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-md">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
            <!--create location group end-->

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



        //edit modal
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
        //             // Populate the form with fetched data
        //             $('#edit-location-group-form').attr('action', '{{ route('group.update', ':id') }}'
        //                 .replace(':id', groupId));
        //             $('#edit-name').val(response.locationGroup.name);
        //             $('#edit-location').val(response.locationGroup.location_id);

        //             // Populate location select options dynamically
        //             let locations = response.locations;
        //             $('#edit-location').empty();
        //             locations.forEach(function(location) {
        //                 $('#edit-location').append(new Option(location.title, location.id));
        //             });

        //             // Show image previews if available
        //             let images = response.locationGroup.images;
        //             $('#image-preview-container').empty(); // Clear any existing images
        //             if (images.length > 0) {
        //                 images.forEach(function(image) {
        //                     let imgPreview = `<div class="file-slot">
    //                         <img src="${image}" alt="Image preview" class="w-24 h-24 object-cover"/>
    //                     </div>`;
        //                     $('#image-preview-container').append(imgPreview);
        //                 });
        //             }
        //         },
        //         error: function() {
        //             alert('Failed to fetch location group data.');
        //         }
        //     });
        // });

        // Edit modal click event
        // $(document).on('click', '.edit-location-group-btn', function() {
        //     let groupId = $(this).data('id'); // Get the ID of the group

        //     // Show the modal
        //     $('#editLocationGroupModal').removeClass('hidden');

        //     // Make an AJAX request to fetch location group data
        //     const url = "{{ route('group.show', ['id' => ':id']) }}".replace(':id', groupId);

        //     $.ajax({
        //         url: url,
        //         method: 'GET',
        //         success: function(response) {
        //             // Populate the form with fetched data
        //             $('#edit-location-group-form').attr('action', '{{ route('group.update', ':id') }}'
        //                 .replace(':id', groupId));
        //             $('#edit-name').val(response.locationGroup.name); // Set group name
        //             $('#edit-location').val(response.locationGroup.location_id); // Set current location

        //             // Populate location select options dynamically
        //             let locations = response.locations;
        //             $('#edit-location').empty(); // Clear existing options
        //             locations.forEach(function(location) {
        //                 let selected = location.id == response.locationGroup.location_id ?
        //                     'selected' : '';
        //                 $('#edit-location').append(new Option(location.title, location.id,
        //                     false, selected));
        //             });

        //             // Show image previews if available
        //             let images = response.images;
        //             $('#image-preview-container').empty(); // Clear any existing images
        //             if (images.length > 0) {
        //                 images.forEach(function(image) {
        //                     let imgPreview = `<div class="file-slot">
        //                 <img src="${image}" alt="Image preview" class="w-24 h-24 object-cover"/>
        //             </div>`;
        //                     $('#image-preview-container').append(imgPreview);
        //                 });
        //             }
        //         },
        //         error: function() {
        //             alert('Failed to fetch location group data.');
        //         }
        //     });
        // });


        // // Close Modal when clicking on the close button
        // $('#closeModalBtn').on('click', function() {
        //     $('#editLocationGroupModal').addClass('hidden');
        // });

        // // Optional: Close modal when clicking outside of the modal content
        // $('#editLocationGroupModal').on('click', function(event) {
        //     if ($(event.target).is('#editLocationGroupModal')) {
        //         $('#editLocationGroupModal').addClass('hidden');
        //     }
        // });








        //add location group
        const slotBoxes = document.querySelectorAll(".slot-box");

        slotBoxes.forEach((slotBox) => {
            const slotBoxModal = slotBox.querySelector(".slot-box-modal");
            const slotModalCloseBtn =
                slotBoxModal.querySelector(".close-modal-btn");
            const slotModalSaveBtn = slotBoxModal.querySelector(".open-modal-btn");
            const slotContent = slotBox.querySelector(".slot-box-content");
            const slotImage = slotBoxModal.querySelector("input[type=file]");
            const overlay = slotBox.querySelector(".overlay")

            function hideModal() {
                slotBoxModal.classList.add("hidden");
            }

            overlay.addEventListener("click", (e) => {
                e.stopPropagation();
                e.preventDefault();
                hideModal()
            })

            const defaultContent = slotContent.innerHTML.trim();

            slotContent.addEventListener("click", (e) => {
                e.stopPropagation();
                e.preventDefault();
                slotBoxModal.classList.remove("hidden");
            });

            slotModalCloseBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                e.preventDefault();
                hideModal()
            });

            slotModalSaveBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                e.preventDefault();
                const imageFile = slotImage.files[0];

                if (imageFile) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        slotContent.innerHTML =
                            `<img src="${e.target.result}" alt="Image Preview" class="w-full h-full object-cover" />`;
                    };

                    reader.readAsDataURL(imageFile);
                }
                hideModal()
            });
        });
    </script>
@endpush
