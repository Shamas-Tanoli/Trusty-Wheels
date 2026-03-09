@extends('admin/layouts/layoutMaster')

@section('title', 'serve Section')

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
'resources/assets/admin/js/serve.js'
])
@endsection
@section('content')
<form id="jobaddform" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="app-ecommerce">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 row-gap-4">
      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-0">Serve Section </h4>
      </div>
      <div class="d-flex align-content-center flex-wrap gap-4">
        <button id="uploadButton" type="submit" class="btn btn-primary">Submit</button>
        <button type="button" id="addSliderBtn" class="btn btn-secondary">Add  Field</button>
      </div>
    </div>

    <div id="slidersContainer">
      @foreach($sliders as $index => $slider)
      <input type="hidden" name="slider_id[]" value="{{ $slider->id }}">
        <div class="card mb-6 slider-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Slider {{ $index + 1 }}</h5>
            <button type="button" class="btn btn-danger btn-sm remove-slider-btn">Remove</button>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title[]" value="{{ $slider->title }}">
              </div>
              <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Sub Title</label>
                <input type="text" class="form-control" name="subtitle[]" value="{{ $slider->sub_title }}">
              </div>
              <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control slider-image" name="image[]" accept="image/*">
                @if($slider->image_path)
                <input type="hidden" name="existing_image[]" value="{{ $slider->image_path }}">
                  <img class="img-preview mt-2" src="{{ asset($slider->image_path) }}" alt="" style="width:100px; height:auto;">
                @else
                  <img class="img-preview mt-2" src="" alt="" style="display:none; width:100px; height:auto;">
                @endif
              </div>
              
             
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</form>

<script>
  let sliderCount = {{ $sliders->count() }};

  function createSliderCard() {
    sliderCount++;
    const card = document.createElement('div');
    card.classList.add('card', 'mb-6', 'slider-card');
    card.innerHTML = `
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Slider ${sliderCount}</h5>
        <button type="button" class="btn btn-danger btn-sm remove-slider-btn">Remove</button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4 mb-3">
            <label class="form-label">Title</label>
            <input type="hidden" name="slider_id[]" value="">
            <input type="text" class="form-control" name="title[]">
          </div>
          <div class="col-12 col-md-4 mb-3">
            <label class="form-label">Sub Title</label>
            <input type="text" class="form-control" name="subtitle[]">
          </div>
          <div class="col-12 col-md-4 mb-3">
            <label class="form-label">Image</label>
            <input type="file" class="form-control slider-image" name="image[]" accept="image/*">
            <img class="img-preview mt-2" src="" alt="" style="display:none; width:100px; height:auto;">
          </div>
         
         
        </div>
      </div>
    `;

    document.getElementById('slidersContainer').appendChild(card);
  }

  // Add Slider Button
  document.getElementById('addSliderBtn').addEventListener('click', createSliderCard);

  // ✅ REMOVE BUTTON EVENT DELEGATION (IMPORTANT FIX)
  document.getElementById('slidersContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-slider-btn')) {
      e.target.closest('.slider-card').remove();
    }
  });

  // ✅ IMAGE PREVIEW EVENT DELEGATION
  document.getElementById('slidersContainer').addEventListener('change', function(e) {
    if (e.target.classList.contains('slider-image')) {
      const file = e.target.files[0];
      const imgPreview = e.target.closest('.col-md-4').querySelector('.img-preview');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          imgPreview.src = event.target.result;
          imgPreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    }
  });

</script>
@endsection