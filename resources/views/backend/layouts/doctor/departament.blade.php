@extends('backend.app')

@section('title', 'FAQ Management| ' . $setting->title ?? 'PrimeCare')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
<!-- Dropify CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />

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
    }

    #modalOverlay.modal-open #modal {
        opacity: 1;
        top: 50%;
    }

</style>
@endpush

@section('content')
<main class="p-6">
    <div class="card bg-white overflow-hidden">
        <div class="card-header">
            <div class="flex justify-between align-middle">
                <h3 class="card-title">Doctors</h3>
                <div>
                    <a href="{{route('department.create.form')}}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                        Add Department
                    </a>
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
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        S/L
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
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
</main>

<!-- Edit Department Modal -->
<div id="editDepartmentModal" style="display:none;">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <div class="flex py-2 w-full justify-between border-b">
            <h3 class="text-lg font-semibold">Edit Department</h3>
            <button id="closeDepartmentModal" class="hover:bg-gray-200 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="update-department-form">
            @csrf
            @method('PUT')
            <input type="hidden" id="department_id" name="department_id">

            <!-- Department Name -->
            <div class="mb-4">
                <label for="edit_department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                <input type="text" id="edit_department_name" name="department_name" class="form-input mt-1 block w-full" required>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="edit_department_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="edit_department_status" name="status" class="form-input mt-1 block w-full">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn bg-success text-white py-2 px-5 rounded-md">Update</button>
        </form>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
<!-- Dropify JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>



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
                url: "{{ route('doctors.department') }}", // Ensure this route is correct
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'status',
                    name: 'status'
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

    $(document).ready(function() {
        const flasher = new Flasher({
            selector: '[data-flasher]',
            duration: 3000,
            options: {
                position: 'top-right',
            },
        });
    });





    $('#close').click(function() {
        $('#modalOverlay').removeClass('modal-open');
        setTimeout(function() {
            $('#modalOverlay').hide();
        }, 200);
    });



    // Open Edit Department Modal
    function editDepartment(id) {
        let url = '{{ route("department.edit", ":id") }}'.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {

                if (response.success) {
                    $('#department_id').val(response.data.id);
                    $('#edit_department_name').val(response.data.department_name);
                    $('#edit_department_status').val(response.data.status);

                    $('#editDepartmentModal').show();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred while fetching the department data.');
            }
        });
    }

    // Close Modal
    $('#closeDepartmentModal').click(function() {
        $('#editDepartmentModal').hide();
    });




</script>
@endpush
