'use strict';
document.addEventListener('DOMContentLoaded', function (e) {
  let amenity_id;
  
  document.addEventListener('click', function (event) {
    let button = event.target.closest('.edit-btn'); 
    if(button){
        let name = button.getAttribute('data-name');
        amenity_id = button.getAttribute('data-id');
        document.getElementById('editname').value = name;
    } 
    
  });
  (function () {
    FormValidation.formValidation(document.getElementById('editPermissionForm'), {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Please enter Vehicle Check name'
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
    }) .on('core.form.valid', function (e) {
      const formData = new FormData(document.getElementById('editPermissionForm')); 
      fetch(`/dashboard/vehicle-check/${amenity_id}/update`, {
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
