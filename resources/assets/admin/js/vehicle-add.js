'use strict';
// for add 
(function () {
  // Comment editor
  const commentEditor = document.querySelector('.comment-editor');
  let quillEditor;
  if (commentEditor) {
    quillEditor = new Quill('.comment-editor', {
      modules: { toolbar: false  },
      placeholder: 'Vehicle Description',
      theme: 'snow'
    });
  
  }

  function showLoader() {
    $.blockUI({
      message:
        '<div class="d-flex justify-content-center">' +
        '<p class="mb-0">Please wait...</p>' +
        '<div class="sk-wave m-0">' +
        '<div class="sk-rect sk-wave-rect"></div>' +
        '<div class="sk-rect sk-wave-rect"></div>' +
        '<div class="sk-rect sk-wave-rect"></div>' +
        '<div class="sk-rect sk-wave-rect"></div>' +
        '<div class="sk-rect sk-wave-rect"></div>' +
        '</div></div>',
      css: {
        backgroundColor: 'transparent',
        color: '#fff',
        border: '0'
      },
      overlayCSS: {
        opacity: 0.5
      }
    });
  }

  function hideLoader() {
    $.unblockUI();
  }


  // previewTemplate: Updated Dropzone default previewTemplate
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
  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone(dropzoneBasic, {
      previewTemplate: previewTemplate,
      url: '#',
      maxFiles: null,
      maxFilesize: 10,
      addRemoveLinks: true,
      acceptedFiles: '.jpg,.jpeg,.png,.webp',
      autoProcessQueue: false,
      init: function () {
        var dz = this;
        // Make Dropzone sortable
        new Sortable(dz.element, {
          group: 'dropzone',
          onEnd(evt) {
            updateSortedFiles(dz);
          }
        });
        // Handle form submission with sorted images and validate
        document.getElementById('uploadButton').addEventListener('click', (event) => {
          const description = document.querySelector('#description');
          description.value = quillEditor.getText().trim();
          console.log(description.value);
          if (dz.files.length < 1) {
            document.getElementById('dropzone-error').innerText = 'At least one image is required.';

          }
            
            updateSortedFiles(dz);
          
        });
      }
    });
    function updateSortedFiles(dz) {
      const sortedFiles = Array.from(dz.element.querySelectorAll('.dz-preview')).map(preview => {
        return dz.files.find(file => file.previewElement === preview);
      });

      dz.files = sortedFiles.filter(f => f);
    }
  }
  //  reset form on clicking reset button
  const resetBtn = document.querySelector('button[type="reset"]');
  resetBtn.addEventListener('click', function () {
    quillEditor.root.innerHTML = '';
    myDropzone.removeAllFiles(true);
  });
  // Select All checkbox click
  const selectAll = document.querySelector('#selectAll'),
    checkboxList = document.querySelectorAll('[type="checkbox"]');
  selectAll.addEventListener('change', t => {
    checkboxList.forEach(e => {
      e.checked = t.target.checked;
    });
  });
  //  form validation and hit api to public vehicle
  const fv = FormValidation.formValidation(document.getElementById('vehicle-add-form'), {
    fields: {
      title: {
        validators: {
          notEmpty: {
            message: 'Title is required'
          },
          stringLength: {
            max: 255,
            message: 'Title must be less than 255 characters'
          }
        }
      },
      description: {
        validators: {
          notEmpty: {
            message: 'Description is required'
          }

        }
      },
      vin_nbr: {
        validators: {
          notEmpty: {
            message: 'VIN Number is required'
          },
          stringLength: {
            max: 255,
            message: 'VIN Number must be less than 255 characters'
          }
        }
      },
      lic_plate_nbr: {
        validators: {
          notEmpty: {
            message: 'License Plate Number is required'
          },
          stringLength: {
            max: 255,
            message: 'License Plate Number must be less than 255 characters'
          }
        }
      },
      status: {
        validators: {
          notEmpty: {
            message: 'Status is required'
          }
        }
      },
      rent: {
        validators: {
          notEmpty: {
            message: 'Rent is required'
          },
          numeric: {
            message: 'Rent must be a number'
          }
        }
      },
      make_id: {
        validators: {
          notEmpty: {
            message: 'Please select a Make'
          }
        }
      },
      vehicle_model_id: {
        validators: {
          notEmpty: {
            message: 'Please select a Vehicle Model'
          }
        }
      },
      location_id: {
        validators: {
          notEmpty: {
            message: 'Please select a Location'
          }
        }
      },
      vehicle_type_id: {
        validators: {
          notEmpty: {
            message: 'Please select a Vehicle Type'
          }
        }
      },
      year: {
        validators: {
          notEmpty: {
            message: 'Year is required'
          },
          numeric: {
            message: 'Year must be a number'
          },
          between: {
            min: 1900,
            max: new Date().getFullYear(),
            message: `Year must be between 1900 and ${new Date().getFullYear()}`
          }
        }
      },
      transmission: {
        validators: {
          notEmpty: {
            message: 'Transmission is required'
          },
          stringLength: {
            max: 50,
            message: 'Transmission must be less than 50 characters'
          }
        }
      },
      fuel_type: {
        validators: {
          notEmpty: {
            message: 'Fuel Type is required'
          },
          stringLength: {
            max: 50,
            message: 'Fuel Type must be less than 50 characters'
          }
        }
      },
      door: {
        validators: {
          notEmpty: {
            message: 'Door count is required'
          },
          numeric: {
            message: 'Door count must be a number'
          },
          between: {
            min: 2,
            max: 10,
            message: 'door count must be between 2 and 10'
          }
        }
      },
      seats: {
        validators: {
          notEmpty: {
            message: 'Seats count is required'
          },
          numeric: {
            message: 'Seats count must be a number'
          },
          between: {
            min: 2,
            max: 15,
            message: 'Seats count must be between 2 and 15'
          }
        }
      },
      'amenities[]': {
        validators: {
          callback: {
            message: 'one amenity Required',
            callback: function (value, validator, $field) {
              return document.querySelectorAll('input[name="amenities[]"]:checked').length > 0;
            }
          }
        }
      }
      // files: {
      //   validators: {
      //     notEmpty: {
      //       message: 'At least one file is required'
      //     },
      //     file: {
      //       extension: 'jpg,jpeg,png,webp',
      //       maxSize: 10240 * 1024, // 10MB
      //       message: 'Please upload a valid image file (jpg, jpeg, png, webp) and not exceed 10MB'
      //     }
      //   }
      // },
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: '.input'
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  })
  .on('core.form.valid', function (e) {
    
    const formData = new FormData(document.getElementById('vehicle-add-form'));

    

    if (myDropzone.files.length < 1) {
      document.getElementById('dropzone-error').innerText = 'At least one image is required.'
      return;
    }
    else{
       document.getElementById('dropzone-error').innerText = ''
    }

    myDropzone.files.forEach((file, index) => {
      formData.append(`files[${index}]`, file);
    });

    showLoader();
    fetch('/dashboard/vehicle/create', {
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
          hideLoader();
        if (data.success) {
          myDropzone.removeAllFiles();
          document.getElementById("resetbtn").click();
          toastr.success(data.message, 'Success');
          setTimeout(() => {
            window.location.href = '/dashboard/vehicle/';
        }, 1000);
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
        hideLoader();
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

  // $('.select2').each(function () {
  //   $(this).on('change', function () {
  //     fv.revalidateField($(this).attr('name'));

  //   });
  // });
})();
