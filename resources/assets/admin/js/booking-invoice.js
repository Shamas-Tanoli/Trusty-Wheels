'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  $('#end_date').on('change', function () {
    let startDate = $('#startdate').val();
    let endDate = $(this).val();
    let bookingId = Number($('#booking_id').val());
    if (endDate && bookingId) {
      $.ajax({
        url: `/dashboard/booking/${bookingId}/calculatePrice`,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
           'Accept': 'application/json'
        },
        data: {
          end_date: endDate,
          start_date: startDate
        },
        success: function (data) {
         

          if (data.success) {
            $('#totaldays').text(data.total_days);
            $('#rent1').text(data.total_rent.toFixed(2));
            $('#totalrent').val(data.total_rent.toFixed(2));
            $('#grandtotal').text('£' + data.after_ticket_plus);
            calculateGrandTotal();
          } else if (data.extra) {
            Swal.fire({
              title: 'Error!',
              text: data.extra,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
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
        },
        error: function (xhr) {
          

          let errorData;
          try {
            errorData = JSON.parse(xhr.responseText); // Parse JSON response
          } catch (e) {
            console.error('Invalid JSON response:', e);
            return;
          }

          if (!errorData.errors) {
            Swal.fire({
              title: 'Error!',
              text: errorData.message || 'Something went wrong.',
              icon: 'error',
              customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
              buttonsStyling: false
            });
            return;
          }

          const errorList = Object.values(errorData.errors)
            .flat()
            .map(
              error =>
                `<li style="font-size: 14px;">
                    <i class="ti text-danger ti-alert-triangle ti-flashing-hover"></i> ${error}
                  </li>`
            )
            .join('');

          Swal.fire({
            title: 'Validation Error!',
            html: `<ul style="list-style: none; padding: 0; margin: 0;">${errorList}</ul>`,
            icon: 'error',
            customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
            buttonsStyling: false
          });
        }
      });
    }
  });

  function calculateGrandTotal() {
    let discount = parseFloat($('#discount').val()) || 0;
    let ticketAmount = parseFloat($('#ticketamount').val()) || 0;
    let totalRent = parseFloat($('#totalrent').val()) || 0;
    let paidAmount = parseFloat($('#paid_amount').val()) || 0;

    let grandTotal = ticketAmount + totalRent - discount;
    let remainingAmount = grandTotal - paidAmount;

    $('#grandtotal').text('£' + grandTotal.toFixed(2));
    $('#discountcheck').text(discount.toFixed(2));
    $('#paidcheck').text('£' + paidAmount.toFixed(2));
    $('#remaning').text('£' + remainingAmount.toFixed(2));
    $('#remaining_amount').val(remainingAmount.toFixed(2));
  }

  $('#discount, #paid_amount').on('input', function () {
    let end_date = $('#end_date').val();

    if (!end_date) {
      $(this).val('');
      Swal.fire({
        title: 'Error!',
        text: 'Please select end date first..',
        icon: 'error',
        customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
        buttonsStyling: false
      });
      return;
    }
    calculateGrandTotal();
  });

  // Initial calculation in case values are pre-filled
  calculateGrandTotal();

  // flat picker for date
  document.querySelectorAll('.flatpickr-date').forEach(element => {
    flatpickr(element, {
      monthSelectorType: 'static'
    });
  });

  //   for store the form final result

  (function () {
    const form = document.getElementById('invoiceformcustom');

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      const booking_id = document.getElementById('booking_id').value;
      const formData = new FormData(form);

      fetch(`/dashboard/booking/${booking_id}/calculatePrice/store`, {
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
            form.reset();
            toastr.success(data.message, 'Success');
           
            
          }
          else if (data.extra) {
            form.reset();
            Swal.fire({
              title: 'Error!',
              text: data.extra,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }
           else {
            
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
