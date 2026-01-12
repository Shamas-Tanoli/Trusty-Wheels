'use strict';

// Add permission form validation
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const form = document.getElementById('jobaddform');
    FormValidation.formValidation(form, {
      fields: {
        driver_id: {
          validators: {
            notEmpty: {
              message: 'Please select a driver'
            }
          }
        },
        vehicle_id: {
          validators: {
            notEmpty: {
              message: 'Please select a vehicle'
            }
          }
        },
        date: {
          validators: {
            notEmpty: {
              message: 'Please select a date'
            }
          }
        },
        'passenger_ids[]': {
                validators: {
                    callback: {
                        message: 'Please select a passenger in every row',
                        callback: function (value, validator, $field) {
                            // Get all passenger selects
                            const selects = document.querySelectorAll('.passenger-select');
                            // Check each one is not empty
                            for (let i = 0; i < selects.length; i++) {
                                if (!selects[i].value) {
                                    return false;
                                }
                            }
                            return true;
                        }
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
        const formData = new FormData(document.getElementById('jobaddform'));
        fetch('/dashboard/job/store', {
          method: 'POST',
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
            // console.log(data);
            if (data.success) {
              form.reset();
              toastr.success(data.message, 'Success');
             formData.reset();
             
             
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

          .catch(error => console.log('Error', error));
      });
  })();
});
