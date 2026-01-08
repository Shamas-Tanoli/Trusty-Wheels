'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (function () {

    const form = document.getElementById('driver-add-form');

    FormValidation.formValidation(form, {
      fields: {

        name: {
          validators: {
            notEmpty: {
              message: 'Driver name is required'
            },
            stringLength: {
              min: 2,
              message: 'Name must be at least 2 characters'
            }
          }
        },

        father_name: {
          validators: {
            notEmpty: {
              message: 'Father name is required'
            }
          }
        },

        cnic: {
          validators: {
            notEmpty: {
              message: 'CNIC is required'
            }
          }
        },

        contact: {
          validators: {
            notEmpty: {
              message: 'Contact number is required'
            }
          }
        },

        emergency_contact: {
          validators: {
             notEmpty: {
              message: 'Contact number is required'
            }
          }
        },

        blood_group: {
          validators: {
            notEmpty: {
              message: 'Blood group is required'
            }
          }
        },

        address: {
          validators: {
            notEmpty: {
              message: 'Address is required'
            }
          }
        },

        city: {
          validators: {
            notEmpty: {
              message: 'City name is required'
            }
          }
        },

        city_id: {
          validators: {
            notEmpty: {
              message: 'Please select a city'
            }
          }
        },

       

        
       
        
        

      },

      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.mb-3'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    })

    // ✅ AJAX Submit after validation success
    .on('core.form.valid', function () {

      const formData = new FormData(form);

      fetch('/dashboard/driver/store', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {

        if (data.success) {
          form.reset();
          toastr.success(data.message, 'Success');

          // clear image previews
          document.querySelectorAll('[id$="Preview"]').forEach(el => el.innerHTML = '');

        } else {

          const errorList = Object.values(data.errors).flat().map(error =>
            `<li style="font-size:14px">
              <i class="ti ti-alert-triangle text-danger"></i> ${error}
            </li>`
          ).join('');

          Swal.fire({
            title: 'Validation Error',
            html: `<ul style="list-style:none;padding:0">${errorList}</ul>`,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary'
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
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      });

    });

  })();
});
