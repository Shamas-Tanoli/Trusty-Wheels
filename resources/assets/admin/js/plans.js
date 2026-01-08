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
              message: 'Please enter a Plan Name'
            }
          }
        },
        area_from_id: {
          validators: {
            notEmpty: {
              message: 'Please enter a Plan Name'
            }
          }
        },
        area_to_id: {
          validators: {
            notEmpty: {
              message: 'Please enter a Plan Name'
            }
          }
        },
        price: {
          validators: {
            notEmpty: {
              message: 'Please enter price'
            },
            numeric: {
              message: 'Price must be a valid number'
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
      fetch('/dashboard/plan/store', {
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
    ajax: '/dashboard/plan/list',
    searchDelay: 1000,

    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'name', name: 'plans.name' },
      { data: 'price', name: 'plans.price' },
      { data: 'area_from', name: 'areaFrom.name' },
      { data: 'area_to', name: 'areaTo.name' },
      { data: 'status', name: 'plans.status' },
    
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
      searchPlaceholder: 'Search Plan',
      paginate: {
        next: '<i class="ti ti-chevron-right ti-sm"></i>',
        previous: '<i class="ti ti-chevron-left ti-sm"></i>'
      }
    },

    buttons: [
      {
        text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Plan</span>',
        className: 'add-new btn btn-primary',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addPermissionModal'
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
          fetch(`/dashboard/plan/delete/${model_id}`, {
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

    let plan_id;

    // EDIT BUTTON CLICK
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.edit-btn');
        if (!btn) return;

        plan_id = btn.dataset.id;

        // Inputs
        $('#edit_name').val(btn.dataset.name);
        $('#edit_price').val(btn.dataset.price);
        $('#edit_status').val(btn.dataset.status);
        


        // Clear previous select2 options
        $('#edit_area_from').empty();
        $('#edit_area_to').empty();

        // Area From
        if (btn.dataset.servicetimeid) {
            let fromOption = new Option(
                btn.dataset.servicetimename,
                btn.dataset.servicetimeid,
                true,
                true
            );
            $('#select2Basicaaaaa').append(fromOption).trigger('change');
        }
        if (btn.dataset.areaFromId) {
            let fromOption = new Option(
                btn.dataset.areaFrom,
                btn.dataset.areaFromId,
                true,
                true
            );
            $('#edit_area_from').append(fromOption).trigger('change');
        }

        // Area To
        if (btn.dataset.areaToId) {
            let toOption = new Option(
                btn.dataset.areaTo,
                btn.dataset.areaToId,
                true,
                true
            );
            $('#edit_area_to').append(toOption).trigger('change');
        }
    });

    // FORM SUBMIT
    document
        .getElementById('editPlanForm')
       document
  .getElementById('editPlanForm')
  .addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/dashboard/plan/${plan_id}/update`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': window.csrfToken,
        'Accept': 'application/json'
      },
      body: formData
    })
      .then(async res => {
        const data = await res.json();

        // ❌ Validation Errors (422)
        if (!res.ok && res.status === 422) {
          let errorsHtml = '';
          Object.values(data.errors).forEach(errArr => {
            errArr.forEach(msg => {
              errorsHtml += `<li>${msg}</li>`;
            });
          });

          Swal.fire({
            title: 'Validation Error!',
            html: `<ul style="text-align:left">${errorsHtml}</ul>`,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });

          return;
        }

        // ❌ Other Errors
        if (!res.ok || data.success === false) {
          Swal.fire({
            title: 'Error!',
            text: data.message ?? 'Something went wrong',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });
          return;
        }

        // ✅ Success
        toastr.success(data.message);
        $('#editPlanModal').modal('hide');
        $('.datatables-permissions')
          .DataTable()
          .ajax.reload(null, false);
      })
      .catch(() => {
        Swal.fire({
          title: 'Error!',
          text: 'Server error. Please try again.',
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
