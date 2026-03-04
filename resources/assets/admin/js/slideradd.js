/**
 * Add Permission Modal JS
 */

'use strict';

// Add permission form validation
document.addEventListener('DOMContentLoaded', function () {

  const form = document.getElementById('jobaddform');

  form.addEventListener('submit', function (e) {
    e.preventDefault(); // page reload rokne ke liye

    const formData = new FormData(form);

    fetch('/dashboard/frontend/slider/store', {
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
          
          document.querySelectorAll('.img-preview').forEach((card)=> card.src = " ")

      } else {

        const errorList = Object.values(data.errors)
          .flat()
          .map(error =>
            `<li style="font-size: 14px;">
              <i class="ti text-danger ti-alert-triangle ti-flashing-hover"></i> ${error}
            </li>`
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

});
