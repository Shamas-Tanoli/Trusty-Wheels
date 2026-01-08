'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  const tableBtnUrl = document.getElementById('table-btn-url').value;

  // for datatable
  (function () {
    $('.datatables-permissions').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/dashboard/driver/list',

        searchDelay: 1000,
       columns: [
    { data: 'DT_RowIndex', orderable: false, searchable: false },

    { data: 'name',  name: 'user.name' },
    { data: 'email', name: 'user.email' },

    { data: 'contact', name: 'drivers.contact' },
    { data: 'status', orderable: false, searchable: false },
    { data: 'action', orderable: false, searchable: false }
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
            searchPlaceholder: 'Search Driver',
            paginate: {
                next: '<i class="ti ti-chevron-right ti-sm"></i>',
                previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        },

        buttons: [
            {
                text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class=" d-sm-inline-block">Add Driver</span>',
                className: 'add-new btn btn-primary ms-2 ms-sm-0 waves-effect waves-light mb-3 mb-md-0',
                action: function () {
                    window.location.href = tableBtnUrl;
                }
            }
        ],
    });
})();






  // for delte
  (function () {
    $(document).on('click', '.delete-confirm', function () {
      let vehicle_id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
          cancelButton: 'btn btn-label-secondary waves-effect waves-light'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
          fetch(`/dashboard/driver/delete/${vehicle_id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': window.csrfToken,
              'Accept': 'application/json'
            }
          })
            .then(response => {
              return response.json();
            })
            .then(data => {
              if (data.success) {
                $('.datatables-permissions').DataTable().ajax.reload(null, false);
                toastr.success(data.message, 'Success');
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: data.message,
                  icon: 'error',
                  customClass: {
                    confirmButton: 'btn btn-primary waves-effect waves-light'
                  },
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
        }
      });
    });
  })();
});
