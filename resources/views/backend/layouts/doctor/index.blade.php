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
                    <a href="{{route('doctor.create-form')}}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
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

<!-- Edit Doctor Form Modal -->
<div id="modalOverlay" style="display:none;">
    <div id="modal" class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <div class="flex py-2 w-full justify-between border-b">
            <button id="close" class="m-4 absolute top-0 right-1 hover:bg-gray-200 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-black" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="update-doctor-form" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" id="doctor_id" name="doctor_id">

    <!-- Name -->
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" class="form-input mt-1 block w-full">
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" class="form-input mt-1 block w-full">
    </div>

    <!-- Department -->
    <div class="mb-4">
        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
        <select id="department" name="department" class="form-input mt-1 block w-full">
            <option value="" disabled selected>Select Department</option>
            <!-- Options will be populated by AJAX -->
        </select>
    </div>

    <!-- Avatar -->
    <div class="mb-4">
        <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
        <input type="file" id="avatar" name="avatar" class="dropify form-input mt-1 block w-full" class="form-input w-full dropify dropify-wrapper1 .dropify-preview dropify-render img" >
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

   
    // Edit Doctor Function
    function editDoctor(id) {
    let url = '{{ route("doctor.edit", ":id") }}';
    url = url.replace(':id', id);

    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const doctor = response.data;

                // Populate the form with fetched data
                $('#name').val(doctor.name);
                $('#email').val(doctor.email);
                $('#department').val(doctor.department);
                $('#doctor_id').val(doctor.id);

                
                const avatarPath = response.avatar_url ? response.avatar_url : '';  
                if (avatarPath) {
                    
                    const avatarUrl =  avatarPath;                   
                    $('#avatar').attr('data-default-file', avatarUrl);                 
                    $('#avatar').dropify('destroy').dropify();  
                }

                // Show the modal
                $('#modalOverlay').show().addClass('modal-open');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred. Please try again later.');
        }
    });
}


    
    $('#close').click(function() {
        $('#modalOverlay').removeClass('modal-open');
        setTimeout(function() {
            $('#modalOverlay').hide();
        }, 200);
    });

  
    $(document).ready(function() {
    
    $.ajax({
        url: "{{ route('doctor.department') }}",  // Update this route to your actual API route
        method: 'GET',
        success: function(response) {
        if (response && response.length > 0) {
            const departmentSelect = $('#department');          
            departmentSelect.empty();
                       
            departmentSelect.append(new Option('Select Department', '', false, false));

            // Populate department dropdown with department names
            response.forEach(function(department) {
                const option = new Option(department.department_name, department.department_name);
                departmentSelect.append(option);
            });

            // If editing a doctor, pre-select the department based on department_name
            const doctorDepartmentName = $('#doctor_department_name').val();  // Assuming doctor_department_name is passed in the form

            if (doctorDepartmentName) {
                // Select the department by department_name
                departmentSelect.val(doctorDepartmentName).trigger('change');
            }
        }
    },
    error: function(xhr, status, error) {
        console.error('Failed to fetch departments:', error);
    }
    });

   

    // Form Submission
    $('#update-doctor-form').submit(function(event) {
        event.preventDefault();  // Prevent default form submission

        var formData = new FormData(this);
        var url = '{{ route("doctor.update", ":id") }}'.replace(':id', $('#doctor_id').val());  // Set the URL with doctor ID

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    // Close modal or perform any necessary action
                    flasher.success(response.message || 'Doctor updated successfully');
                    location.reload();
                } else {
                    flasher.error('Error updating doctor');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error submitting form:', error);
                alert('An error occurred while updating the doctor.');
            }
        });
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
 
</script>
@endpush
