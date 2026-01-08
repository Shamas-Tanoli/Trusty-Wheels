'use strict';
$(function () {
  $('.select2').each(function () {
    let $select = $(this),
      url = $select.data('url'),
      dependId = $select.data('depend'),
      dependUrl = $select.data('depend-url');

    // Initialize Select2
    $select.wrap('<div class="position-relative"></div>').select2({
      placeholder: $select.data('placeholder') || 'Select value',
      dropdownParent: $select.parent(),
      minimumInputLength: 0,
      allowClear: true,
    });

    // If there is a selected value, set it properly
    let selectedValue = $select.find('option:selected').val();

    if (dependUrl) {
      $('#' + dependId)
        .on('change', function () {
          let _id = $(this).val();
          if(selectedValue){
            $select.val(selectedValue).trigger('change').prop('disabled', false);
          }
          else{
            $select.val(null).trigger('change').prop('disabled', false);
          }
          
         
          if (_id) {
            $select.select2({
              placeholder: $select.data('placeholder') || 'Select value',
              dropdownParent: $select.parent(),
              minimumInputLength: 0,
              allowClear: true,
              ajax: ajaxConfig(dependUrl, { id: _id })
            });
           
          }
        })
        .trigger('change');
    } else if (url) {
      $select.select2({
        placeholder: $select.data('placeholder') || 'Select value',
        dropdownParent: $select.parent(),
        minimumInputLength: 0,
        allowClear: true,
        ajax: ajaxConfig(url)
      });
    }
  });

  $('form').on('reset', function () {
    $(this).find('.select2').val(null).trigger('change');
    $(this).find('select').empty();
});

  function ajaxConfig(url, extraParams = {}) {
    return {
      url,
      type: 'GET',
      dataType: 'json',
      delay: 500,
      headers: { 'X-CSRF-TOKEN': window.csrfToken },
      data: params => {
        let data = { search: params.term || '' };
        if (Object.keys(extraParams).length > 0) {
          data = Object.assign(data, extraParams);
        }
        return data;
      },
      processResults: data => ({ results: data.map(item => ({ id: item.id, text: item.name })) }),
      cache: true
    };
  }

  // Function to set Select2 preselected option properly
  function setSelect2SelectedOption($select, value) {
    if (value) {
      let selectedOption = $select.find(`option[value="${value}"]`);
      if (selectedOption.length) {
        $select.val(value).trigger('change');
      }
    }
  }
});