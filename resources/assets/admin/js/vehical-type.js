'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  // for insert record
  (function () {
    const form = document.getElementById('addPermissionForm');
    const fv = FormValidation.formValidation(form, {
      fields: {
        
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter Vehical Type '
            },
            stringLength: {
              min: 2,
              message: 'The Vehical Type must be more than 2 characters long'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function (e) {
      const formData = new FormData(document.getElementById('addPermissionForm'));
      fetch('/dashboard/vehicle-type/create', {
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
            form.reset();
            toastr.success(data.message, 'Success');
            $('.select2').val(null).trigger('change');
            $('#addPermissionModal').modal('hide');
            $('.datatables-permissions').DataTable().ajax.reload(null, false);
          } else {
            console.log(data);
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
          console.error('Error:', error);
          Swal.fire({
            title: 'Error!',
            text: error.message || 'Something went wrong. Please try again.',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
        });
    });

  })();

  // for datatable
  (function () {
    $('.datatables-permissions').DataTable({
      processing: true,
      serverSide: true,
      ajax: '/dashboard/vehicle-type/list',
      searchDelay: 1000,
      columns: [
        // { data: 'id', name: 'id' },
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name', orderable: false, searchable: true },
        { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
        { data: 'actions', name: 'actions', orderable: false, searchable: false }
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
        searchPlaceholder: 'Search Vehicle Type',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: [
        {
          text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Vehicle Type</span>',
          className: 'add-new btn btn-primary mb-6 mb-md-0 waves-effect waves-light',
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
  })();

  //  for delete
  (function () {
    $(document).on('click', '.delete-confirm', function () {
      let vehicle_type_id = $(this).data('id');
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
          fetch(`/dashboard/vehicle-type/delete/${vehicle_type_id}`, {
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

  // for edit

  (function () {
   
    let vehicle_type_id;

    document.addEventListener('click', function (event) {
      let button = event.target.closest('.edit-btn');
      if (button) {
        vehicle_type_id = button.getAttribute('data-id');
        let name = button.getAttribute('data-name');
        document.getElementById('editname').value = name;
      }
    });

    const fv = FormValidation.formValidation(document.getElementById('editPermissionForm'), {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter Vehicle Type'
            },
            stringLength: {
              min: 2,
              message: 'The Vehicle Type must be more than 2 characters long'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-sm-9'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function (e) {
      const formData = new FormData(document.getElementById('editPermissionForm'));
      fetch(`/dashboard/vehicle-type/${vehicle_type_id}/edit`, {
        method: 'post',
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
          if (data.success) {
            toastr.success(data.message, 'Success');
            $('#editPermissionModal').modal('hide');
            $('.datatables-permissions').DataTable().ajax.reload(null, false);
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

  
});
