'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAccSettings = document.querySelector("#formAccountSettings");

    let fv = FormValidation.formValidation(formAccSettings, {
      fields: {
          name: {
              validators: {
                  notEmpty: { message: "Please enter your name" }
              }
          },
          email: {
              validators: {
                  notEmpty: { message: "Please enter your email" },
                  emailAddress: { message: "Please enter a valid email address" }
              }
          },
          currentPassword: {
              validators: {
                  callback: {
                      message: "Please enter your current password",
                      callback: function (input) {
                          const newPassword = document.querySelector("#newPassword").value;
                          return newPassword.length === 0 || input.value.length > 0;
                      }
                  }
              }
          },
          newPassword: {
              validators: {
                  
                  stringLength: {
                      min: 6,
                      message: "New password must be at least 6 characters long"
                  }
              }
          },
          confirmPassword: {
              validators: { 
                  identical: {
                      compare: function () {
                          return document.querySelector("#newPassword").value;
                      },
                      message: "Passwords do not match"
                  }
              }
          }
      },
      plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
              eleValidClass: '',
              rowSelector: '.col-md-4'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
          instance.on('plugins.message.placed', function (e) {
              if (e.element.parentElement.classList.contains('input-group')) {
                  e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
              }
          });
      }
  });

  fv.on('core.form.valid', function (e) {
    const formData = new FormData(formAccSettings);
    fetch('/dashboard/admin/detail/update', {
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
        if (data.success) {
       
          toastr.success(data.message, 'Success');
        $('#currentPassword').val('');
        $('#newPassword').val('');
        $('#confirmPassword').val('');
        }
        else if (data.extra) {
       
          toastr.error(data.extra, 'error');
        }  else {
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
