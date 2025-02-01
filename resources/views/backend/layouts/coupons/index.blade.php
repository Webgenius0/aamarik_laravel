@extends('backend.app')

@section('title', 'Coupon Management| ' . $setting->title ?? 'PrimeCare')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
<!-- Dropify CSS for file input styling -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<!-- Tailwind CSS for layout and styling -->
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
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
        max-height: 80%;

        overflow-y: auto;

        padding: 20px;

    }

    #modalOverlay.modal-open #modal {
        opacity: 1;
        top: 50%;
    }

    #modal .modal-content {
        max-height: 70vh;

        overflow-y: auto;

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
                    <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                        onclick="ShowCreateUpdateModal()">
                        Create Coupon
                    </button>
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
                                        Code
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Discount Type
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Discount Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usage Limit
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usage Count
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Start Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        End Date
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

{{-- modal start --}}
<div id="modalOverlay" style="display:none;">
    <div id="modal" class="rounded-2xl max-w-2xl">
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
        <div class="px-8 py-4">
            <form id="createUpdateForm" class="max-w-6xl w-full mx-auto space-y-4"
                enctype="multipart/form-data">

                <h1 class="flex align-left h1">Create Coupon</h1>
                <input type="hidden" name="id" id="coupon_id">

                {{-- favicon --}}



                <!-- Coupon Fields -->

                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                    <div class="flex flex-col md:w-1/2">
                        <label for="discount_type" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-input w-full" required>
                            <option value="" disabled {{ old('discount_type') ? '' : 'selected' }}>Select discount type...</option>
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('discount_type')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="flex flex-col md:w-1/2">
                        <label for="discount_amount" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Discount Amount</label>
                        <input name="discount_amount" type="text" class="form-input w-full" id="discount_amount" placeholder="Enter discount amount.." value="{{old('discount_amount')}}">

                        @error('brand')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                    <div class="flex flex-col md:w-1/2">
                        <label for="usage_limit" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Usage Limit</label>
                        <input  name="usage_limit" type="number" class="form-input w-full" id="usage_limit" placeholder="Enter usage limit.." value="{{old('usage_limit')}}">
                        @error('usage_limit')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="start_date" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Start Date</label>
                        <input type="date" name="start_date" class="form-input w-full" id="start_date" placeholder="Enter start date..." value="{{ old('start_date')  }}"
                        ></input>

                        @error('start_date')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>


                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                    <div class="flex flex-col md:w-1/2">
                        <label class="text-lg font-medium mb-2 md:mb-0 flex align-left">End Date</label>
                        <input type="date"  name="end_date" type="text" class="form-input w-full" id="end_date" placeholder="Enter end date.." value="{{ old('end_date') }}"
                        >
                        @error('end_date')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="coupon" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Coupon</label>
                        <input name="code" type="text" class="form-input w-full" id="code" placeholder="Enter coupon.." value="{{ old('code') }}">
                        @error('coupon')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-center mt-4">

                    <button type="submit"
                        class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-auto flex">Save</button>


                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
{{-- Dropify --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
{{--Flashar--}}
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
{{-- Ck Editor --}}
<script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    function ShowCreateUpdateModal() {
        $('#createUpdateForm')[0].reset();
        $('#medicine_id').val('');
        $('#modalTitle').html('Create New Medicine');
        $('#modalOverlay').show().addClass('modal-open');
    }

    $('#close').click(function() {
        var modal = $('#modalOverlay');
        modal.removeClass('modal-open');
        setTimeout(function() {
            modal.hide();
        }, 200);
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
                url: "{{ route('coupon.index') }}",
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'discount_type',
                    name: 'discount_type'
                },
                {
                    data: 'discount',
                    name: 'discount'
                },
                {
                    data: 'usage_limit',
                    name: 'usage_limit'
                },
                {
                    data: 'used_count',
                    name: 'used_count'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
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

    // Submit Form
    $('#createUpdateForm').on('submit', function(e) {
        e.preventDefault();
        var faqId = $('#coupon_id').val();
        var url = faqId ? "{{ route('coupon.update', ':id') }}".replace(':id', faqId) : "{{ route('coupon.store') }}";
        var method = faqId ? "PUT" : "POST";
        // Make the AJAX request
        $.ajax({
            type: method,
            url: url,
            data: $(this).serialize(),
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            dataType: 'json',
            success: function(resp) {
                console.log(resp);

                // Reload DataTable
                $('#data-table').DataTable().ajax.reload();

                // Handle response
                if (resp.success === true) {
                    // Show success message
                    flasher.success(resp.message);

                    // Close modal
                    $('#modalOverlay').removeClass('modal-open');
                    setTimeout(function() {
                        $('#modalOverlay').hide();
                    }, 200);
                } else if (resp.errors) {
                    // Show first error message
                    flasher.error(resp.errors[0]);
                } else {
                    // Show warning message
                    flasher.warning(resp.message);
                }
            },
            error: function(error) {
                // Show error message
                flasher.error('An error occurred. Please try again.');
                console.error(error);
            }
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
                deleteCoupon(id);
            }
        });
    };


    function deleteCoupon(id)
    {
        var url = "{{ route('coupon.destroy', ':id') }}".replace(':id', id)
        $.ajax({
            type: "DELETE",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(resp) {

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



     // Status Change Confirm Alert
     function ShowStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }

     // Status Change
     function statusChange(id) {
            var url = '{{ route('coupon.status.update', ':id') }}';
            $.ajax({
                type: "GET",
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
                        flasher.warning(resp.message);
                    }
                }, // success end
                error: function(error) {
                    flasher.error(error);
                }
            })
        }

   function editCoupon(id) {
    let route = '{{ route('coupon.edit', ':id') }}';
    route = route.replace(':id', id);

    $.ajax({
        type: "GET",
        url: route,
        success: function(resp) {
            console.log(resp.data); // Ensure the response data structure is as expected

            if (resp.success === true) {
                // Populate the form fields with data from the response
                $('#coupon_id').val(resp.data.id);
                $('#discount_type').val(resp.data.discount_type); // Set discount type dropdown
                $('#discount_amount').val(resp.data.discount_amount); // Set discount amount
                $('#usage_limit').val(resp.data.usage_limit); // Set usage limit

                // Convert the date format from 'YYYY-MM-DDTHH:mm:ss.sssZ' to 'YYYY-MM-DD'
                const startDate = resp.data.start_date.split('T')[0]; // Take the date part only
                const endDate = resp.data.end_date.split('T')[0]; // Take the date part only

                // Set the formatted dates
                $('#start_date').val(startDate);
                $('#end_date').val(endDate);

                $('#code').val(resp.data.code); // Set coupon code

                // Change the modal title to "Update Coupon"
                $('#modalTitle').html('Update Coupon');
                $('#modalOverlay').show().addClass('modal-open');
            } else if (resp.errors) {
                flasher.error(resp.errors[0]); // Show errors if any
            } else {
                flasher.warning(resp.message); // Show warning if no success
            }
        },
        error: function(error) {
            flasher.error('Error while fetching data.'); // Show error if AJAX fails
        }
    });
}



</script>
@endpush
