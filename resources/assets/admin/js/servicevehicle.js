'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  // for insert record
  (function () {
    const form = document.getElementById('addPermissionForm');
    const select2Elements = $('.select2');

    const fv = FormValidation.formValidation(form, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter Vehicle Name'
            }
          }
        },
        number_plate: {
          validators: {
            notEmpty: {
              message: 'Please enter Registration Number'
            }
          }
        },
        image: {
          validators: {
            notEmpty: {
              message: 'Please Select Vehicle Image'
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
      fetch('/dashboard/service-vehicle/store', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          Accept: 'application/json'
        },
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            form.reset();

            toastr.success(data.message, 'Success');
            $('#addPermissionModal').modal('hide');
            $('.datatables-permissions').DataTable().ajax.reload(null, false);
          } else {
            const errorList = Object.values(data.errors)
              .flat()
              .map(
                error => `<li style="font-size: 14px;">
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
    ajax: '/dashboard/servicetime/list',
    searchDelay: 1000,

    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'service_name', name: 'service_name', orderable: false, searchable: true },
      { data: 'service_time', name: 'service_time', orderable: false, searchable: true },
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
      searchPlaceholder: 'Search Service Time',
      paginate: {
        next: '<i class="ti ti-chevron-right ti-sm"></i>',
        previous: '<i class="ti ti-chevron-left ti-sm"></i>'
      }
    },

    buttons: [
      {
        text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Service Time</span>',
        className: 'add-new btn btn-primary mb-6 mb-md-0',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addPermissionModal'
        },
        init: function (api, node) {
          $(node).removeClass('btn-secondary');
        }
      }
    ]
  });
})();


  //  for delete
  (function () {
    $(document).on('click', '.delete-confirm', function () {
      let model_id = $(this).data('id');
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
          fetch(`/dashboard/servicetime/delete/${model_id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': window.csrfToken,
              Accept: 'application/json'
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
    const select2Elements = $('.select2');
    let model_id;

    document.addEventListener('click', function (event) {
      let button = event.target.closest('.edit-btn');

      if (button) {
        model_id = button.getAttribute('data-id');
        let name = button.getAttribute('data-name');
        let make_id = button.getAttribute('data-make-id');
        let makeName = button.getAttribute('data-make-name');
        document.getElementById('editname').value = name;
        var newOption = new Option(makeName, make_id, true, true);
        $('#slectmake').append(newOption).trigger('change');
      }
    });

    const fv = FormValidation.formValidation(document.getElementById('editPermissionForm'), {
      fields: {
        service_id: {
          validators: {
            notEmpty: {
              message: 'Please select a Service'
            }
          }
        },
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter Service Time'
            },
            stringLength: {
              min: 2,
              message: 'The Service Time must be more than 2 characters long'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-10'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function (e) {
      const formData = new FormData(document.getElementById('editPermissionForm'));
      fetch(`/dashboard/servicetime/${model_id}/update`, {
        method: 'post',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          Accept: 'application/json'
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
            $('.datatables-permissions').DataTable().ajax.reload(null, true);
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

    select2Elements.each(function () {
      $(this).on('change', function () {
        // fv.updateFieldStatus($(this).attr('name'), 'NotValidated');
        fv.revalidateField($(this).attr('name'));
      });
    });
  })();
});
