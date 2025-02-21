@extends('backend.app')

@section('title', 'Create Role')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header flex justify-between items-center p-4 bg-gray-100 border-b">
                <h3 class="card-title text-lg font-semibold">Create Role</h3>

                <a href="{{ route('roles.index') }}"
                   class="text-white bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded-md shadow-md transition duration-300">
                    ← Back
                </a>
            </div>

            <div class="p-4">
                <form id="roleForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium">Role Name</label>
                        <input type="text" id="role_name" name="role" class="border rounded p-2 w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Select Permissions</label>
                        <div id="permissions-container"></div>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Role</button>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            loadPermissions();

            function loadPermissions() {
                $.ajax({
                    url: "{{ route('permissions.index') }}",
                    type: "GET",
                    success: function (response) {
                        if (response.success) {
                            console.log(response.permissions); // Debugging

                            let permissionsHtml = '';
                                response.permissions.forEach(function (permission) {
                                    permissionsHtml += `
                                    <label class="inline-flex items-center mr-3">
                                        <input type="checkbox" name="permissions[]" value="${permission.id}" class="mr-2">
                                        ${permission.name}
                                    </label>
                                    <br>
                                `;
                            });

                            $("#permissions-container").html(permissionsHtml);
                        }
                    }
                });
            }

            $("#roleForm").submit(function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "{{ route('role.store') }}",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire("Success!", response.message, "success");
                            $("#roleForm")[0].reset();
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        Swal.fire("Error!", errors ? errors.join("<br>") : "Something went wrong!", "error");
                    }
                });
            });
        });
    </script>
@endpush
