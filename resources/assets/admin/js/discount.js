'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (function () {
    const form = document.getElementById('addPromoForm');

    const fv = FormValidation.formValidation(form, {
      fields: {
       

        type: {
          validators: {
            notEmpty: {
              message: 'Please select Discount type'
            }
          }
        },

        value: {
          validators: {
            notEmpty: {
              message: 'Please enter value'
            },
            numeric: {
              message: 'Value must be numeric'
            },
            greaterThan: {
              min: 1,
              message: 'Value must be greater than 0'
            }
          }
        },
        person: {
          validators: {
            notEmpty: {
              message: 'Person  is required'
            },
            integer: {
              message: 'Person must be an integer'
            },
            greaterThan: {
              min: 1,
              message: 'Person must be at least 1'
            }
          }
        },

        is_active: {
          validators: {
            notEmpty: {
              message: 'Please select status'
            }
          }
        }
      },

      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          rowSelector: '.col-12',
          eleValidClass: ''
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function () {
      const formData = new FormData(form);

      fetch('/dashboard/discount/store', {
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
    $('#addPromoModal').modal('hide');
    $('.datatables-promo').DataTable().ajax.reload(null, false);

  } else {

    const errorList = Object.values(data.errors)
      .flat()
      .map(error => `
        <li style="font-size: 14px;">
          <i class="ti text-danger ti-alert-triangle"></i> ${error}
        </li>
      `)
      .join('');

    Swal.fire({
      title: 'Error!',
      html: `<ul style="list-style:none;padding:0">${errorList}</ul>`,
      icon: 'error',
      customClass: { confirmButton: 'btn btn-primary' },
      buttonsStyling: false
    });
  }
})
.catch(error => {
  Swal.fire({
    title: 'Error!',
    text: 'Something went wrong. Please try again.',
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
      ajax: '/dashboard/promo-code/list',
      searchDelay: 1000,
      columns: [
           
            { data: 'code', name: 'code' ,orderable: false, searchable: false },
            { data: 'type', name: 'type',orderable: false, searchable: false  },
            { data: 'value', name: 'value',orderable: false, searchable: false  },
            { data: 'usage_limit', name: 'usage_limit',orderable: false, searchable: false  }, // Usage
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'start_date', name: 'start_date',orderable: false, searchable: false  }, // Expiry
            { data: 'end_date', name: 'end_date',orderable: false, searchable: false  }, // Expiry
            { data: 'action', name: 'action', orderable: false, searchable: false }
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
    $('.dt-buttons.btn-group.flex-wrap').css('display', 'none');
})();


  /* =========================
     DELETE PROMO CODE
  ========================== */


 
 $(document).on('click', '.delete-btn', function() {
    let id = $(this).data('id');
    if (!id) return;

    if (confirm('Are you sure you want to delete this promo code?')) {
        $.ajax({
            url: '/dashboard/promo-code/' + id,
            type: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.status === 'success'){
                    alert(response.message);
                    $('.datatables-promo').DataTable().ajax.reload(); // refresh table
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Something went wrong!');
            }
        });
    }
});

// Click Edit button
$(document).on('click', '.edit-btn', function() {
    let id = $(this).data('id');

    $.get('/dashboard/promo-code/' + id + '/edit', function(res) {
        if(res.status === 'success') {
            let data = res.data;
            let modal = $('#editPromoModal');

            modal.find('input[name="id"]').val(data.id);
            modal.find('input[name="code"]').val(data.code);
            modal.find('select[name="type"]').val(data.type);
            modal.find('input[name="value"]').val(data.value);
            modal.find('input[name="start_date"]').val(data.start_date);
            modal.find('input[name="end_date"]').val(data.end_date);
            modal.find('input[name="usage_limit"]').val(data.usage_limit);
            modal.find('select[name="is_active"]').val(data.is_active ? 'active' : 'inactive');

            modal.modal('show');
        } else {
            alert(res.message);
        }
    });
});



(function () {
  const form = document.getElementById('editPromoForm');

  const fv = FormValidation.formValidation(form, {
    fields: {
      code: {
        validators: {
          notEmpty: { message: 'Please enter promo code' },
          stringLength: { min: 3, message: 'Promo code must be at least 3 characters' }
        }
      },
      type: {
        validators: { notEmpty: { message: 'Please select promo type' } }
      },
      value: {
        validators: {
          notEmpty: { message: 'Please enter value' },
          numeric: { message: 'Value must be numeric' },
          greaterThan: { min: 1, message: 'Value must be greater than 0' }
        }
      },
      start_date: {
        validators: { notEmpty: { message: 'Start date is required' } }
      },
      end_date: {
        validators: {
          notEmpty: { message: 'End date is required' },
          callback: {
            message: 'End date must be after or equal to start date',
            callback: function (input) {
              const startDate = form.querySelector('[name="start_date"]').value;
              const endDate = input.value;
              if (!startDate || !endDate) return true;
              return new Date(endDate) >= new Date(startDate);
            }
          }
        }
      },
      usage_limit: {
        validators: {
          notEmpty: { message: 'Usage limit is required' },
          integer: { message: 'Usage limit must be an integer' },
          greaterThan: { min: 1, message: 'Usage limit must be at least 1' }
        }
      },
      is_active: {
        validators: { notEmpty: { message: 'Please select status' } }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({ rowSelector: '.col-12', eleValidClass: '' }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    const formData = new FormData(form);

    // Send to update route
    fetch('/dashboard/promo-code/update', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': window.csrfToken,
    'Accept': 'application/json'
  },
  body: formData
})
.then(async response => {
  const data = await response.json();
  return { status: response.status, body: data };
})
.then(({ status, body }) => {

  // ✅ SUCCESS
  if (status === 200 && body.status === 'success') {
    form.reset();
    toastr.success(body.message, 'Success');
    $('#editPromoModal').modal('hide');
    $('.datatables-promo').DataTable().ajax.reload(null, false);
    return;
  }

  // ❌ VALIDATION ERRORS (422)
  if (status === 422 && body.status === 'validation_error') {
    const errorList = Object.values(body.errors)
      .flat()
      .map(error => `
        <li style="font-size:14px;">
          <i class="ti ti-alert-triangle text-danger"></i> ${error}
        </li>
      `)
      .join('');

    Swal.fire({
      title: 'Validation Error',
      html: `<ul style="list-style:none;padding:0;margin:0;">${errorList}</ul>`,
      icon: 'warning',
      customClass: {
        confirmButton: 'btn btn-primary'
      },
      buttonsStyling: false
    });
    return;
  }

  // ❌ NOT FOUND / LOGICAL ERRORS
  if (status === 404 || body.status === 'error') {
    Swal.fire({
      title: 'Error!',
      text: body.message || 'Record not found',
      icon: 'error',
      customClass: {
        confirmButton: 'btn btn-primary'
      },
      buttonsStyling: false
    });
    return;
  }

  // ❌ SERVER ERROR
  Swal.fire({
    title: 'Server Error',
    text: body.message || 'Something went wrong',
    icon: 'error',
    customClass: {
      confirmButton: 'btn btn-primary'
    },
    buttonsStyling: false
  });

})
.catch(error => {
  console.error(error);
  Swal.fire({
    title: 'Network Error',
    text: 'Please check your internet connection',
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
