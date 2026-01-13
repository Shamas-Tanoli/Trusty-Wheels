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
        url: '/dashboard/booking/list', // Initial URL
        data: function (d) {
          d.status = $('#statusFilter').val();
        }
      },
      searchDelay: 1000,
      columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'customer_name', name: 'customer.name' , orderable: false, searchable: false},
            { data: 'plan_name', name: 'plan.name' , orderable: false, searchable: false},
            { data: 'service_name', name: 'service.name', orderable: false, searchable: false },
            { data: 'service_time', name: 'serviceTime.name', orderable: false, searchable: false },
            { data: 'town_name', name: 'town.name' , orderable: false, searchable: false},
            { data: 'passengers_count', name: 'passengers_count', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
