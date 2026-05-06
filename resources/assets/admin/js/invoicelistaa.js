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
            {
                text: '<i class="ti ti-plus ti-xs me-2"></i><span>Add Invoice</span>',
                className: 'btn btn-primary add-new',
                action: function () {
                    $('#addInvoiceModal').modal('show');
                }
            }
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

//   
  
});
