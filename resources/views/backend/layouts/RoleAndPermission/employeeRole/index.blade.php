@extends('backend.app')

@section('title', 'Employee Role Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <h3 class="card-title">Employee Role Management</h3>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto custom-scroll">
                    <table id="data-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">S/L</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">User Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Roles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Permissions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Role Management Modal -->
    <div id="role-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-5 rounded-md w-96">
            <h3 class="text-lg font-bold mb-3">Manage Roles</h3>
            <select id="role-select" class="select2 w-full">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <button id="attach-role" class="mt-3 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-md">Attach Role</button>
            <button id="close-modal" class="mt-3 bg-danger-500 text-white px-4 py-2 rounded-md">Close</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            let table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee.roles.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_email', name: 'user_email' },
                    { data: 'roles', name: 'roles', orderable: false, searchable: false },
                    { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            let userId;

            $(document).on('click', '.manage-role', function () {
                userId = $(this).data('user');
                $('#role-modal').removeClass('hidden');
            });

            $('#close-modal').click(function () {
                $('#role-modal').addClass('hidden');
            });

            $('#attach-role').click(function () {
                let roleId = $('#role-select').val();
                $.post("{{ route('employee.attach.role', ':id') }}".replace(':id', userId), {
                    _token: "{{ csrf_token() }}",
                    role: roleId
                }).done(response => {
                    Swal.fire('Success!', response.message, 'success');
                    $('#role-modal').addClass('hidden');
                    table.ajax.reload();
                });
            });

            // Detach Role with AJAX
            $(document).on('click', '.remove-role', function () {
                let userId = $(this).data('user');
                let roleId = $(this).data('role');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the role from the user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("{{ route('employee.detach.role', ':id') }}".replace(':id', userId), {
                            _token: "{{ csrf_token() }}",
                            role: roleId
                        }).done(response => {
                            if (response.success) {
                                Swal.fire('Removed!', response.message, 'success');

                                // Update the table
                                table.ajax.reload();

                                // Update the permissions dynamically
                                let permissionsHtml = response.permissions.map(permission =>
                                    `<span class="bg-green-500 text-white px-2 py-1 rounded text-xs">${permission}</span>`
                                ).join(' ');

                                $(`#permissions-${userId}`).html(permissionsHtml);
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

@endpush
