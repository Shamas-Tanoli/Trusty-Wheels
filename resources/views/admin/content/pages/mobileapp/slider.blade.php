@extends('admin/layouts/layoutMaster')

@section('title', 'Mobile App Slider')

@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/quill/typography.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/admin/vendor/libs/quill/editor.scss',
'resources/assets/admin/vendor/libs/select2/select2.scss',
'resources/assets/admin/vendor/libs/dropzone/dropzone.scss',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/quill/quill.js',
'resources/assets/admin/vendor/libs/select2/select2.js',
'resources/assets/admin/vendor/libs/dropzone/dropzone.js',
'resources/assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/admin/vendor/libs/sortablejs/sortable.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js',

])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/forms-selects.js',
'resources/assets/admin/js/slideradd.js'
])
@endsection
s
@section('content')
<form id="jobaddform">
  <div class="app-ecommerce">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 row-gap-4">
      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-0">Mobile App Slider</h4>
      </div>
      <div class="d-flex align-content-center flex-wrap gap-4">
        <button id="uploadButton" type="submit" class="btn btn-primary">Submit</button>
        <button type="button" id="addSliderBtn" class="btn btn-secondary">Add More Slider</button>
      </div>
    </div>

    <div id="slidersContainer">
      <!-- Slider cards will be appended here -->
    </div>

  </div>
</form>

<script>
let sliderCount = 0;

function createSliderCard() {
  sliderCount++;
  
  const card = document.createElement('div');
  card.classList.add('card', 'mb-6');
  card.innerHTML = `
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Slider ${sliderCount}</h5>
      <button type="button" class="btn btn-danger btn-sm remove-slider-btn">Remove</button>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" name="title[]">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Sub Title</label>
          <input type="text" class="form-control" name="subtitle[]">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Image</label>
          <input type="file" class="form-control slider-image" name="image[]" accept="image/*">
          <img class="img-preview mt-2" src="" alt="" style="display:none; width:100px; height:auto;">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Order</label>
          <input type="number" class="form-control" name="order[]">
        </div>
        <div class="col-md-4 d-flex align-items-center gap-2">
          <input class="form-check-input" name="is_active[]" type="checkbox">
          <label class="form-check-label">Active</label>
        </div>
      </div>
    </div>
  `;

  // Append card to container
  const container = document.getElementById('slidersContainer');
  container.appendChild(card);

  // Remove button functionality
  const removeBtn = card.querySelector('.remove-slider-btn');
  removeBtn.addEventListener('click', function() {
    container.removeChild(card);
  });

  // Add image preview functionality
  const fileInput = card.querySelector('.slider-image');
  const imgPreview = card.querySelector('.img-preview');
  fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        imgPreview.src = e.target.result;
        imgPreview.style.display = 'block';
      }
      reader.readAsDataURL(file);
    }
  });
}

// Initialize first slider on page load
// createSliderCard();

// Add more slider button
document.getElementById('addSliderBtn').addEventListener('click', createSliderCard);
</script>
@endsection