/**
 * Add Permission Modal JS
 */

'use strict';

// Add permission form validation
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const form = document.getElementById('addPermissionForm');
    FormValidation.formValidation(form, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter service name'
            },
            stringLength: {
              min: 2,
              message: 'The name must be more than 2 characters long'
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
    }) // Fetch request after successful validation
      .on('core.form.valid', function (e) {
        const formData = new FormData(document.getElementById('addPermissionForm'));
        fetch('/dashboard/service/store', {
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

document.addEventListener('DOMContentLoaded', function (e) {
  $(document).on('click', '.delete-confirm', function () {
    let locationId = $(this).data('id');

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
        fetch(`/dashboard/service/delete/${locationId}`, {
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
});
