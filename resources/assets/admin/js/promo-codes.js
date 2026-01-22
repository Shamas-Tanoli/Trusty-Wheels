'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (function () {

    const form = document.getElementById('addPromoForm');

    const fv = FormValidation.formValidation(form, {
      fields: {
        code: {
          validators: {
            notEmpty: { message: 'Please enter promo code' },
            stringLength: {
              min: 3,
              message: 'Promo code must be at least 3 characters'
            }
          }
        },
        value: {
          validators: {
            notEmpty: { message: 'Please enter value' },
            numeric: { message: 'Value must be numeric' }
          }
        },
        start_date: {
          validators: {
            notEmpty: { message: 'Start date is required' }
          }
        },
        end_date: {
          validators: {
            notEmpty: { message: 'End date is required' }
          }
        },
        usage_limit: {
            validators: {
                integer: { message: 'Usage limit must be an integer' }
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
    }).on('core.form.valid', function () {

      const formData = new FormData(form);

      fetch('/admin/promo-codes/store', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data) {
            toastr.success('Promo code added successfully', 'Success');
            form.reset();
            $('#addPromoModal').modal('hide');
            $('.datatables-promo').DataTable().ajax.reload(null, false);
          }
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: error.message,
            icon: 'error',
            customClass: { confirmButton: 'btn btn-primary' },
            buttonsStyling: false
          });
        });
    });

  })();

  /* =========================
     DATATABLE
  ========================== */
  (function () {

    $('.datatables-promo').DataTable({
        
      processing: true,
      serverSide: true,
      ajax: '/admin/promo-codes',
      searchDelay: 1000,
      columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'code' },
        { data: 'type' },
        { data: 'value' },
        {
          data: 'used_count',
          render: (d, t, r) => `${d} / ${r.usage_limit ?? '∞'}`
        },
        {
          data: 'is_active',
          render: d => d ? 'Active' : 'Inactive'
        },
        { data: 'end_date' },
        { data: 'actions', orderable: false, searchable: false }
      ],
      dom:
        '<"row mx-1"' +
        '<"col-md-3"l>' +
        '<"col-md-9"<"dt-action-buttons text-end"Bf>>' +
        '>t' +
        '<"row"' +
        '<"col-md-6"i>' +
        '<"col-md-6"p>' +
        '>',
    
      language: {
        searchPlaceholder: 'Search Promo Code',
        paginate: {
          next: '<i class="ti ti-chevron-right"></i>',
          previous: '<i class="ti ti-chevron-left"></i>'
        }
      }
    });
$(document).ready(function() {
    // Hide the element with class dt-buttons
    $('.dt-buttons').css('display', 'none');

});
  })();

  /* =========================
     DELETE PROMO CODE
  ========================== */
  (function () {

    $(document).on('click', '.delete-confirm', function () {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'This promo code will be deleted!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(result => {
        if (result.value) {
          fetch(`/admin/promo-codes/delete/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': window.csrfToken,
              'Accept': 'application/json'
            }
          })
            .then(res => res.json())
            .then(() => {
              toastr.success('Promo code deleted', 'Success');
              $('.datatables-promo').DataTable().ajax.reload(null, false);
            });
        }
      });
    });

  })();

  /* =========================
     EDIT PROMO CODE
  ========================== */
  (function () {

    let promo_id;

    $(document).on('click', '.edit-btn', function () {
      promo_id = $(this).data('id');

      fetch(`/admin/promo-codes/${promo_id}`)
        .then(res => res.json())
        .then(data => {
          $('#editPromoForm [name="id"]').val(data.id);
          $('#editPromoForm [name="code"]').val(data.code);
          $('#editPromoForm [name="type"]').val(data.type);
          $('#editPromoForm [name="value"]').val(data.value);
          $('#editPromoForm [name="start_date"]').val(data.start_date);
          $('#editPromoForm [name="end_date"]').val(data.end_date);
          $('#editPromoModal').modal('show');
        });
    });

    const fv = FormValidation.formValidation(
      document.getElementById('editPromoForm'),
      {
        fields: {
          code: {
            validators: {
              notEmpty: { message: 'Promo code is required' }
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
      }
    ).on('core.form.valid', function () {

      const formData = new FormData(document.getElementById('editPromoForm'));

      fetch('/admin/promo-codes/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
        .then(res => res.json())
        .then(() => {
          toastr.success('Promo code updated', 'Success');
          $('#editPromoModal').modal('hide');
          $('.datatables-promo').DataTable().ajax.reload(null, false);
        });
    });

  })();

});
