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
            { data: 'status', name: 'status' },
            { data: 'due_date', name: 'due_date' },
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


  (function(){
    $(document).ready(function() {

    // View Passengers click
    $('.datatables-permissions').on('click', '.view-passengers', function() {
        var bookingId = $(this).data('id');

        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
        // AJAX request
        $.ajax({
            url: 'booking/detail/' + bookingId,
            type: 'GET',
            success: function(res) {
              
                if(res.success) {
                    var tbody = '';
                    $.each(res.data, function(i, passenger) {
                        tbody += '<tr>';
                        tbody += '<td>' + (i+1) + '</td>';
                        tbody += '<td>' + passenger.name + '</td>';
                        tbody += '<td>' + passenger.pickup_time + '</td>';
                        tbody += '<td>' + passenger.dropoff_time + '</td>';
                        tbody += '<td>' + passenger.pickup_location + '</td>';
                        tbody += '<td>' + passenger.dropoff_location + '</td>';
                        tbody += '</tr>';
                    });
                    $('#passengerTable tbody').html(tbody);
                    $('#passengerModal').modal('show'); // Show modal
                } else {
                    alert(res.message);
                }
            },
            error: function(err) {
                alert('Something went wrong');
            }
        });
    });

});

  })();
 


  (function () {
    
 document.addEventListener('click', function (e) {
    if (e.target.classList.contains('editbtnnnn')) {
        console.log(e.target.dataset.id);
        document.getElementById('bookingid').value = e.target.dataset.id;
        document.getElementById('statussss').value = e.target.dataset.status;
    }
});

    const form = document.getElementById('addPermissionModal');
    form.addEventListener('submit', function (e) {
      
      e.preventDefault();
      let formData = new FormData(document.getElementById('addPermissionForm'));
      fetch('/dashboard/booking/status/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
           'Accept': 'application/json'
        },
        body: formData
      })
        .then(response => {
          return response.json();
        })
        .then(data => {
          // console.log(data);
          if (data.success) {
            
            toastr.success(data.message, 'Success');
            $('#addPermissionModal').modal('hide');
            $('.datatables-permissions').DataTable().ajax.reload(null, false);
          } else if (data.extra) {
            Swal.fire({
              title: 'Error!',
              text: data.extra,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          } else {
            const errorList = Object.values(data.errors)
              .flat()
              .map(
                error =>
                  `<li style="font-size: 14px;">
              <i class="ti text-danger ti-alert-triangle ti-flashing-hover"></i> ${error}</li>`
              )
              .join('');

            Swal.fire({
              title: 'Error!',
              html: `<ul style="list-style: none; padding: 0; margin: 0;">${errorList}</ul>`,
              icon: 'error',
              customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
              buttonsStyling: false
            });
          }
        })

        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: error.message,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
        });
    });
  })();

  // flat picker for date
  
});
