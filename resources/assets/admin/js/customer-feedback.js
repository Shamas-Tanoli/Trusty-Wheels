'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  const tableBtnUrl = document.getElementById('table-btn-url').value;

  // for datatable
  (function () {
    // Initialize the DataTable
    var table = $('.datatables-permissions').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '/dashboard/feedback/list/json', // Initial URL
        data: function (d) {
          d.status = $('#statusFilter').val();
        }
      },
      searchDelay: 1000,
      columns: [
         { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'customer_name', name: 'customer.name' },
        { data: 'message', name: 'message' },
        { data: 'rating', name: 'rating' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ],
      pageLength: 10,
      lengthMenu: [10, 25, 50, 100],
      dom:
        '<"row mx-1"' +
        '<"col-sm-12 col-md-3" l>' +
        '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap"<"me-4 mt-n6 mt-md-0"f>B>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: '',
        searchPlaceholder: 'Search Vehicle Name',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: [
        {
          text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Booking</span>',
          className: 'd-none add-new btn btn-primary mb-6 mb-md-0 waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addPermissionModal'
          },
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
          }
        }
      ]
    });

    // Add a select option for filtering by status

    // Handle the filtering logic
    $('#statusFilter').on('change', function () {
      table.ajax.reload();
    });
  })();

 $(document).on('click', '.approve-feedback', function () {

    let id = $(this).data('id');

    $.ajax({

        url: 'approve/' + id,
        type: 'POST',

          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            if (response.success) {

                alert(response.message);

                $('.dataTable').DataTable().ajax.reload();
            }
        },

        error: function () {

            alert('Something went wrong');
        }
    });
});


$(document).on('click', '.delete-feedback', function () {

    let id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this feedback?')) {
        return;
    }

    $.ajax({

        url: 'delete/' + id,
        type: 'post',

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            if (response.success) {

                alert(response.message);

                $('.dataTable').DataTable().ajax.reload();
            }
        },

        error: function (xhr) {

            console.log(xhr.responseText);

            alert('Something went wrong');
        }
    });
});
  

  // flat picker for date
});
