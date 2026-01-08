/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    offCanvasForm = $('#offcanvasAddUser');

  // ticket datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '/dashboard/ticket/',

      columns: [
        { data: 'DT_RowIndex' },
        { data: 'booking' },
        { data: 'amount' },
        { data: 'reason' },
        { data: 'datetime' },
        { data: 'action' }
      ].map(col => ({ ...col, searchable: false })),
      dom:
        '<"row"' +
        '<"col-md-2"<"ms-n2"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Driver or Vehicle',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: []
    });
  }

 

  //  dropzone
  const previewTemplate = `<div class="dz-preview dz-file-preview">
  <div class="dz-details">
    <div class="dz-thumbnail">
      <img data-dz-thumbnail>
      <span class="dz-nopreview">No preview</span>
      <div class="dz-success-mark"></div>
      <div class="dz-error-mark"></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
    </div>
    <div class="dz-filename" data-dz-name></div>
    <div class="dz-size" data-dz-size></div>
  </div>
</div>`;

const fileupload = document.querySelector('#fileupload');
let myDropzone;
if (fileupload) {
  Dropzone.autoDiscover = false;

  myDropzone = new Dropzone(fileupload, {
    previewTemplate: previewTemplate,
    url: '#',
    autoProcessQueue: false,
    maxFiles: 1,
    addRemoveLinks: true,
    maxFilesize: 5,
    acceptedFiles: 'image/*,application/pdf',
    init: function () {
      this.on('addedfile', function (file) {
        
        if (this.files.length > 1) {
          this.removeFile(this.files[0]);
        }

        if (!file.type) {
          console.error('File type is undefined. Removing file.');
          this.removeFile(file);
          return;
        }

        // Check if the file type is valid
        if (!file.type.startsWith('image/') && file.type !== 'application/pdf') {
          console.error('Invalid file type. Only images and PDFs are allowed.');
          this.removeFile(file);
          return;
        }

        // Handle PDF files
        if (file.type === 'application/pdf') {
          // Hide the image tag
          const img = file.previewElement.querySelector('img');
          img.style.display = 'none';
          const noPreview = file.previewElement.querySelector('.dz-nopreview');
          noPreview.style.display = 'none';

          // Create an embed element for PDF
          const embed = document.createElement('embed');
          embed.src = URL.createObjectURL(file);
          embed.width = '100';
          embed.height = '100';
          embed.type = 'application/pdf';
          // Add the embed element to the thumbnail
          file.previewElement.querySelector('.dz-thumbnail').appendChild(embed);
        }
      });
    }
  });

  // Function to add existing file to Dropzone
}

  async function addExistingFileToDropzone(fileUrl) {
    if (!fileUrl) return;

    // Extract file name from URL
    const fileName = fileUrl.split('/').pop();

    // Determine file type based on extension
    const fileType = fileName.endsWith('.pdf') ? 'application/pdf' : 'image/*';

    try {
      // Fetch the file as a blob
      const response = await fetch(fileUrl);
      const blob = await response.blob();

      // Create a File object
      const file = new File([blob], fileName, { type: fileType });

      // Add the file to Dropzone
      myDropzone.files.push(file);
      myDropzone.emit('addedfile', file);

      // Handle PDF files
      if (fileType === 'application/pdf' && file.previewElement) {
        const previewElement = file.previewElement;

        // Hide image preview if it exists
        const img = previewElement.querySelector('img');
        if (img) img.style.display = 'none';

        // Hide "No Preview" text if it exists
        const noPreview = previewElement.querySelector('.dz-nopreview');
        if (noPreview) noPreview.style.display = 'none';

        // Check if an embed tag already exists
        let existingEmbed = previewElement.querySelector('embed');
        if (!existingEmbed) {
          // Create and add an embed element for PDF preview
          const embed = document.createElement('embed');
          embed.src = fileUrl; // Use the file URL directly
          embed.width = '100';
          embed.height = '100';
          embed.type = 'application/pdf';

          previewElement.querySelector('.dz-thumbnail')?.appendChild(embed);
        }
      } else {
        // Generate thumbnail for images
        myDropzone.emit('thumbnail', file, fileUrl);
      }

      myDropzone.emit('complete', file);
    } catch (error) {
      console.error('Error fetching the file:', error);
    }
  }

  // user form validation
  const form = document.getElementById('ticketaddform');
  const fv = FormValidation.formValidation(form, {
    fields: {
      booking_id: {
        validators: {
          notEmpty: {
            message: 'Please select a booking vehicle'
          }
        }
      },
      amount: {
        validators: {
          notEmpty: {
            message: 'Please enter the amount'
          },
          numeric: {
            message: 'The value must be a number'
          }
        }
      },
      reason: {
        validators: {
          notEmpty: {
            message: 'Please enter the reason'
          }
        }
      },
      date_time: {
        validators: {
          notEmpty: {
            message: 'Please select a date and time'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: '.mb-6'
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function (e) {
    // e.preventDefault();
    // const submitEvent = new Event('submit', { cancelable: true });
    // if (!form.dispatchEvent(submitEvent)) {
    //   return;
    // }

    const formMode = $('#form_mode').val();

    let url = formMode === 'add' ? '/dashboard/ticket/store' : '/dashboard/ticket/edit';



    const formData = new FormData(form);
    if (myDropzone && myDropzone.files.length > 0) {
      formData.append('file', myDropzone.files[0]);
    }

    fetch(url, {
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
          $('.datatables-users').DataTable().ajax.reload(null, true);
          offCanvasForm.offcanvas('hide');
          fv.resetForm(true);
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

  // flat picker
  document.querySelectorAll('.flatpickr-date').forEach(element => {
    flatpickr(element, {
      enableTime: true,
      dateFormat: 'Y-m-d H:i'
    });
  });

  document.getElementById('cancelButton').addEventListener('click', function () {
    myDropzone.removeAllFiles(true);
    fv.resetForm(true);
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    var dtrModal = $('.dtr-bs-modal.show');
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }
    $('#form_mode').val('edit');
    var ticket_id = $(this).data('id');
    var ticket_amount = $(this).data('amount');
    var ticket_reason = $(this).data('reason');
    var ticket_datetime = $(this).data('datetime');
    var ticket_file = $(this).data('file');
    var booking_id = $(this).data('booking-id');
    var vehicle_title = $(this).data('vehicle-title');
    var newOption = new Option(vehicle_title, booking_id, true, true);
    $('#booking_id').append(newOption).trigger('change');

    // Set the form fields
    $('#ticket_id').val(ticket_id);
    $('#aamount').val(ticket_amount);
    $('#reason').val(ticket_reason);
    $('#flatpickr-datetime').val(ticket_datetime);

    // Set the file in the dropzone
    myDropzone.removeAllFiles(true);

    // Add the existing file to Dropzone
    if (ticket_file) {
      addExistingFileToDropzone(ticket_file);
    }

    $('#offcanvasAddUserLabel').html('Edit Ticket');
  });


   // changing the title
   $('.add-new').on('click', function () {
    $('#ticket_id').val(''); //reseting input field
    $('#form_mode').val('add');
    $('#offcanvasAddUserLabel').html('Add Ticket');
    myDropzone.removeAllFiles(true);
    fv.resetForm(true);
    $('#booking_id').val(null).trigger('change');

  });

  // end
});

//   $.ajax({
//     data: $('#addNewUserForm').serialize(),
//     url: `${baseUrl}user-list`,
//     type: 'POST',
//     success: function (status) {
//       dt_user.draw();
//       offCanvasForm.offcanvas('hide');

//       // sweetalert
//       Swal.fire({
//         icon: 'success',
//         title: `Successfully ${status}!`,
//         text: `User ${status} Successfully.`,
//         customClass: {
//           confirmButton: 'btn btn-success'
//         }
//       });
//     },
//     error: function (err) {
//       offCanvasForm.offcanvas('hide');
//       Swal.fire({
//         title: 'Duplicate Entry!',
//         text: 'Your email should be unique.',
//         icon: 'error',
//         customClass: {
//           confirmButton: 'btn btn-success'
//         }
//       });
//     }
//   });
// });

// // clearing form data when offcanvas hidden
// offCanvasForm.on('hidden.bs.offcanvas', function () {
//   fv.resetForm(true);
