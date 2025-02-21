@extends('backend.app')

@section('title', 'Edit Role')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header flex justify-between">
                <h3 class="card-title text-lg font-semibold">Edit Role</h3>
                <a href="{{ route('roles.index') }}"
                   class="text-white bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded-md shadow-md transition duration-300">
                    ← Back
                </a>
            </div>

            <div class="p-6">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="role_id" value="{{ $role->id }}">

                    <div class="mb-4">
                        <label for="role_name" class="block text-sm font-medium text-gray-700">Role Name</label>
                        <input type="text" id="role_name" name="role" class="mt-1 p-2 w-full border rounded-md"
                               required value="{{ old('role', $role->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permissions</label>
                        <div id="permissions-container" class="p-3 bg-gray-100 rounded-md">
                            @foreach($allPermissions as $permission)
                                <label class="inline-flex items-center mr-3">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="mr-2"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Role</button>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#editRoleForm').on('submit', function (e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let roleId = $('#role_id').val();
                let url = "{{ route('role.update', ':id') }}".replace(':id', roleId);

                $.ajax({
                    type: "PUT",
                    url: url,
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success')
                                .then(() => window.location.href = "{{ route('roles.index') }}");
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    }
                });
            });
        });
    </script>
@endpush
