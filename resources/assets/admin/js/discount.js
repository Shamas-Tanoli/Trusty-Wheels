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
              .map(
                error => `
        <li style="font-size: 14px;">
          <i class="ti text-danger ti-alert-triangle"></i> ${error}
        </li>
      `
              )
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
      ajax: '/dashboard/discount/list',
      searchDelay: 1000,
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'type', name: 'type', orderable: false, searchable: false },
        { data: 'value', name: 'value', orderable: false, searchable: false },
        { data: 'person', name: 'person', orderable: false, searchable: false },
        { data: 'status', name: 'status', orderable: false, searchable: false },

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
        searchPlaceholder: 'Search DIscount',
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

  $(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');
    if (!id) return;

    if (confirm('Are you sure you want to delete this promo code?')) {
      $.ajax({
        url: '/dashboard/discount/' + id,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          if (response.status === 'success') {
            alert(response.message);
            $('.datatables-promo').DataTable().ajax.reload();
          } else {
            alert(response.message);
          }
        },
        error: function (xhr) {
          alert('Something went wrong!');
        }
      });
    }
  });

  // Click Edit button
  $(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');

    $.get('/dashboard/discount/' + id + '/edit', function (res) {
      if (res.status === 'success') {
        let data = res.data;
        let modal = $('#editPromoModal');

        modal.find('input[name="id"]').val(data.id);
        modal.find('select[name="type"]').val(data.type);
        modal.find('input[name="value"]').val(data.value);
        modal.find('input[name="person"]').val(data.person);
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
            type: {
                validators: { notEmpty: { message: 'Please select Discount type' } }
            },
            value: {
                validators: {
                    notEmpty: { message: 'Please enter value' },
                    numeric: { message: 'Value must be numeric' },
                    greaterThan: { min: 1, message: 'Value must be greater than 0' }
                }
            },
            person: {
                validators: {
                    notEmpty: { message: 'Person is required' },
                    integer: { message: 'Person must be an integer' },
                    greaterThan: { min: 1, message: 'Person must be at least 1' }
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

        fetch('/dashboard/discount/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async (response) => {
            const data = await response.json();

            if (!response.ok) {
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach((field) => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            // Add Bootstrap invalid feedback
                            input.classList.add('is-invalid');

                            let feedback = input.nextElementSibling;
                            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                input.parentNode.appendChild(feedback);
                            }
                            feedback.innerText = data.errors[field][0];
                        }
                    });
                } else {
                    alert(data.message || 'Something went wrong');
                }
            } else {
                // Success
                $('#editPromoModal').modal('hide');
                $('.datatables-promo').DataTable().ajax.reload(null, false);
                alert(data.message);
                // Optional: close modal or reset form
                form.reset();
                // Remove invalid classes
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Server error. Please try again.');
        });
    });
})();

});
