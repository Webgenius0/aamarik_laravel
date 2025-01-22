@extends('backend.app')

@section('title', 'FAQ Management| ' . $setting->title ?? 'PrimeCare')

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
                <h3 class="card-title">Social Media</h3>
                <div>
                    <a href="{{ route('doctor.create') }}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                        Add Doctor
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
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Avatar
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



<!-- Edit Doctor Form -->
<!-- Edit Doctor Modal -->
<div id="modalOverlay" style="display:none;">
    <div id="modal" class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
    <div class="flex py-2 w-full justify-between border-b">

<button id="close"
    class="m-4 absolute top-0 right-1 hover:bg-gray-200 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-black"
    type="button">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>
</div>
        <form id="update-doctor-form" method="POST">
            @csrf
            @method('PUT')
            <input type="text" hidden id="">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="form-input mt-1 block w-full">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="form-input mt-1 block w-full">
            </div>
            <div class="mb-4">
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <input type="text" id="department" name="department" class="form-input mt-1 block w-full">
            </div>
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="form-input mt-1 block w-full">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="form-input mt-1 block w-full">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn bg-success text-white py-2 px-5 rounded-md">Update</button>
        </form>
    </div>
</div>


@endsection


@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>


<script>
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
                url: "{{ route('doctor.index') }}", // Ensure this route is correct
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'avatar',
                    name: 'avatar'
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


    function ShowCreateUpdateModal() {
            $('#createUpdateForm')[0].reset();
            $('#faq_id').val('');
            $('#modalTitle').html('Create New FAQ');
            $('#modalOverlay').show().addClass('modal-open');
        }

        $('#close').click(function() {
            var modal = $('#modalOverlay');
            modal.removeClass('modal-open');
            setTimeout(function() {
                modal.hide();
            }, 200);
        });


        //delete confirmation
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


            //delete
        function deleteItem(id) {
            var url = '{{ route('doctor.delete', ':id') }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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



           // This function will be called when the Edit button is clicked
// Function to show the edit modal
function editDoctor(id) {
    let url = '{{ route("doctor.edit", ":id") }}';
    url = url.replace(':id', id);

    $.ajax({
        url: url, // Adjust this route based on your setup
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const doctor = response.data;

                // Populate the form with the fetched data
                $('#name').val(doctor.name);
                $('#email').val(doctor.email);
                $('#department').val(doctor.department);
                $('#status').val(doctor.status);

                // Set the form action to update the doctor
                $('#update-doctor-form').attr('action', '/doctor/' + doctor.id);

                // Show the modal
                $('#modalOverlay').addClass('modal-open');
            } else {
                alert(response.message); // Handle error
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred. Please try again later.');
        }
    });
}

// Close the modal when the "Close" button is clicked
$('#closeModal').click(function() {
    $('#modalOverlay').removeClass('modal-open');
});

// Close the modal after form submission
$('#update-doctor-form').submit(function(event) {
    event.preventDefault();
    
    var formData = new FormData(this);
    var url = $(this).attr('action');
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success) {
                $('#modalOverlay').removeClass('modal-open');
                $('#data-table').DataTable().ajax.reload(); // Reload the data table
                flasher.success(response.message); // Show success message
            } else {
                flasher.error(response.errors[0] || response.message); // Show error message
            }
        },
        error: function(xhr, status, error) {
            flasher.error('An error occurred while updating the doctor.');
        }
    });
});

        
</script>
@endpush