@extends('backend.app')

@section('title', 'Role Management | ' . ($setting->title ?? 'PrimeCare'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Modal Styles */
        #modalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none; /* Initially hidden */
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #modalOverlay.modal-open {
            display: block;
            opacity: 1;
        }

        #modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
            max-width: 500px;
            width: 100%;
            border-radius: 8px;
            z-index: 10000;
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between align-middle">
                    <h3 class="card-title">Role Management</h3>
                    <div>
                        <a href="{{ route('roles.add')  }}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                            Role Add
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto custom-scroll">
                    <table id="data-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500">S/L</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500">Permissions</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500">Action</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#permissions').select2({
                placeholder: "Select permissions",
                allowClear: true,
                width: '100%'
            });

            // DataTable initialization
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'permissions', name: 'permissions' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });



        // Delete Confirm
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
        }

        // Delete Button
        function deleteItem(id) {
            var url = '{{ route('role.delete', ':id') }}'.replace(':id', id);
            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"), // Add CSRF token
                },
                success: function(resp) {
                    console.log(resp);
                    // Reload DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show success toast message
                        flasher.success(resp.message);
                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function(error) {
                    // If any error occurs
                    console.error(error);
                }
            });
        }

    </script>
@endpush
