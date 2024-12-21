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
                                value="{{ old('group_name') }}" required />
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
                                                    accept="image/jpeg, image/png, image/jpg, image/gif" onchange="validateFile(this)" />
                                            </fieldset>
                                            <fieldset>
                                                <label class="block text-sm font-medium text-gray-700"
                                                    for="slot-location-{{ $i }}">Location</label>
                                                <select id="slot-location-{{ $i }}" name="slotLocation[]"
                                                    class="mt-1 block w-full border rounded-md p-2 border-gray-300">
                                                    <option value="" selected disabled>Select a Location</option>
                                                    @forelse ($locations as $location)
                                                        <option value="{{ $location->id }}"
                                                            {{ old('slotLocation.' . $i) == $location->id ? 'selected' : '' }}>
                                                            {{ $location->title }}</option>
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

        // Function to validate file size and type
        function validateFile(input) {
            const file = input.files[0];

            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Only jpeg, png, jpg, gif are allowed.');
                    input.value = ''; // Clear the file input
                    return;
                }

                // Validate file size (2MB = 2048 KB)
                const maxSize = 2048 * 1024; // 2048 KB in bytes
                if (file.size > maxSize) {
                    alert('File is too large. Maximum file size is 2MB.');
                    input.value = ''; // Clear the file input
                    return;
                }
            }
        }

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
            var url = '{{ route('group.destroy', ':id') }}';
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
