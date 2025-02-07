@extends('backend.app')

@section('title', 'Department Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">

    <style>
        /* Modal Centering Fix */
        #departmentModal {
            display: none; /* Hidden by default */
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5); /* Dim background */
            z-index: 9999; /* Ensures it appears above everything */
        }

        #departmentModal > .modal-content {
            position: relative;
            max-width: 500px;
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between align-middle">
                    <h3 class="card-title">Departments</h3>
                    <button onclick="openDepartmentModal()" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                        Add Department
                    </button>
                </div>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table id="department-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">S/L</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Create/Edit Department Modal -->
    <div id="departmentModal">
        <div class="modal-content">
            <div class="flex py-2 w-full justify-between border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Add Department</h3>
                <button id="closeDepartmentModal" class="hover:bg-gray-200 rounded-full p-1" onclick="closeDepartmentModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="department-form">
                @csrf
                <input type="hidden" id="department_id" name="department_id">

                <!-- Department Name -->
                <div class="mb-4">
                    <label for="department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                    <input type="text" id="department_name" name="department_name" class="form-input mt-1 block w-full" required>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="form-input mt-1 block w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn bg-success text-white py-2 px-5 rounded-md">Save</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#department-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('department.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        // Open Modal for Create or Edit
        function openDepartmentModal(id = null) {
            if (id) {
                let route = "{{ route('department.edit', ':id') }}".replace(':id', id);
                $.get(route, function(data) {
                    $('#department_id').val(data.id);
                    $('#department_name').val(data.department_name);
                    $('#status').val(data.status);
                    $('#modalTitle').text('Edit Department');
                    $('#departmentModal').fadeIn().css("display", "flex");
                });
            } else {
                $('#department_id').val('');
                $('#department_name').val('');
                $('#status').val('active');
                $('#modalTitle').text('Add Department');
                $('#departmentModal').fadeIn().css("display", "flex");
            }
        }

        // Close Modal
        function closeDepartmentModal() {
            $('#departmentModal').fadeOut();
        }

        // Save or Update Department
        $('#department-form').submit(function(event) {
            event.preventDefault();

            let formData = $(this).serialize();
            let id = $('#department_id').val();

            let url = id ? "{{ route('department.update', ':id') }}".replace(':id', id) : "{{ route('department.store') }}";
            let type = id ? 'POST' : 'POST';

            $.ajax({
                url: url,
                type: type,
                data: formData,
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success');
                    closeDepartmentModal();
                    $('#department-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                }
            });
        });

        // Delete Department
        function deleteDepartment(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleteUrl = "{{ route('department.delete', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('#department-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush
