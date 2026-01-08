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
        email: {
          validators: {
            notEmpty: {
              message: 'Email is required'
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

        verification_status: {
          validators: {
            notEmpty: {
              message: 'Please select a Status'
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

        'cnic_images[]': {
          validators: {
            notEmpty: {
              message: 'Please upload CNIC images'
            }
          }
        },

        'license_images[]': {
          validators: {
            notEmpty: {
              message: 'Please upload license images'
            }
          }
        },

        profile_image: {
          validators: {
            notEmpty: {
              message: 'Profile image is required'
            }
          }
        },
        'verification_image': {
          validators: {
            notEmpty: {
              message: 'Please upload verification image'
            }
          }
        },
        'other_document[]': {
          validators: {
            notEmpty: {
              message: 'Please upload other documents'
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
        toastr.success(data.message, 'Success');
        return;
    }

    let errorHtml = '';

    // ✅ Validation errors
    if (data.errors && typeof data.errors === 'object') {
        errorHtml = Object.values(data.errors)
            .flat()
            .map(err => `
                <li style="font-size:14px">
                    <i class="ti ti-alert-triangle text-danger"></i> ${err}
                </li>
            `).join('');

    // ✅ SQL / Exception error
    } else if (data.error) {
        errorHtml = `
            <li style="font-size:14px">
                <i class="ti ti-alert-triangle text-danger"></i> ${data.error}
            </li>
        `;

    // ✅ Fallback
    } else {
        errorHtml = `
            <li style="font-size:14px">
                <i class="ti ti-alert-triangle text-danger"></i> Something went wrong
            </li>
        `;
    }

    Swal.fire({
        title: 'Error',
        html: `<ul style="list-style:none;padding:0">${errorHtml}</ul>`,
        icon: 'error',
        customClass: {
            confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
    });
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
