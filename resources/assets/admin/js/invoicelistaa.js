'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  const tableBtnUrl = document.getElementById('table-btn-url').value;

  // for datatable
 (function () {
    var table = $('.datatables-invoice').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/dashboard/invoice/list', // Laravel route
            data: function (d) {
                d.status = $('#statusFilter').val();
            }
        },
        searchDelay: 500,
        columns: [
            { data: 'customer_name', name: 'customer.name' },
            { data: 'invoice_date', name: 'invoice_for_date' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'discounted_total', name: 'discounted_total' },
            { data: 'after_discount', name: 'after_discount' },
            { data: 'paid_amount', name: 'paid_amount' },
{ data: 'remaining_amount', name: 'remaining_amount' },
            { data: 'discount_type', name: 'discount_type' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        dom:
            '<"row mx-1"<"col-md-3"l><"col-md-9"<"dt-action-buttons text-end"Bf>>>' +
            't' +
            '<"row"<"col-md-6"i><"col-md-6"p>>',
        language: {
            searchPlaceholder: 'Search Customer Name',
            paginate: {
                next: '<i class="ti ti-chevron-right ti-sm"></i>',
                previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        },
        buttons: [
           
        ]
    });

    $('#statusFilter').on('change', function () {
        table.ajax.reload();
    });
})();

// model show 
$(document).on('click', '.change-status', function () {

    let id = $(this).data('id');
    let status = $(this).data('status');

    $('#invoice_id').val(id);
    $('#invoice_status').val(status);

    $('#statusModal').modal('show');
});

$(document).on('click', '.change-status', function () {

    $('#invoice_id').val($(this).data('id'));

    $('#invoice_status').val($(this).data('status'));

    $('#statusModal').modal('show');
});

$('#saveStatus').on('click', function () {

    $.ajax({
        url: 'change-status',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            invoice_id: $('#invoice_id').val(),
            status: $('#invoice_status').val(),
            paid_amount: $('#paid_amount').val()
        },

        success: function (response) {

            $('#statusModal').modal('hide');

            $('.datatables-invoice').DataTable().ajax.reload();

            // Success Alert
            alert(response.message);
        },

        error: function (xhr) {

            let errorMessage = 'Something went wrong';

            // Laravel validation errors
            if (xhr.responseJSON) {

                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                // Validation errors
                if (xhr.responseJSON.errors) {

                    errorMessage = '';

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                }
            }

            // Error Alert
            alert(errorMessage);
        }
    });

});
});
