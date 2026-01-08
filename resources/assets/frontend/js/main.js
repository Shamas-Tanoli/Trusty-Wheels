/************* Main Js File ************************
    Template Name: gauto
    Author: Themescare
    Version: 1.0
    Copyright 2019
*************************************************************/

/*------------------------------------------------------------------------------------
    
JS INDEX
=============

01 - Main Slider JS
02 - Select JS
03 - Clockpicker JS
04 - Service Slider JS
05 - Testimonial Slider JS
06 - Responsive Menu JS
07 - Back To Top
08 - vehicle page toggle btn
09 - preloader




-------------------------------------------------------------------------------------*/

(function ($) {
  'use strict';

  window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  jQuery(document).ready(function ($) {
    /* 
		=================================================================
		01 - Main Slider JS
		=================================================================	
		*/

    $('.gauto-slide').owlCarousel({
      animateOut: 'fadeOutLeft',
      animateIn: 'fadeIn',
      items: 2,
      nav: true,
      dots: false,
      autoplayTimeout: 9000,
      autoplaySpeed: 5000,
      autoplay: true,
      loop: true,
      navText: ["<img src='assets/frontend/img/prev-1.png'>", "<img src='assets/frontend/img/next-1.png'>"],
      mouseDrag: true,
      touchDrag: true,
      responsive: {
        0: {
          items: 1
        },
        480: {
          items: 1
        },
        600: {
          items: 1
        },
        750: {
          items: 1
        },
        1000: {
          items: 1
        },
        1200: {
          items: 1
        }
      }
    });

    $('.gauto-slide').on('translate.owl.carousel', function () {
      $('.gauto-main-slide h2').removeClass('animated fadeInUp').css('opacity', '0');
      $('.gauto-main-slide p').removeClass('animated fadeInDown').css('opacity', '0');
      $('.gauto-main-slide .gauto-btn').removeClass('animated fadeInDown').css('opacity', '0');
    });
    $('.gauto-slide').on('translated.owl.carousel', function () {
      $('.gauto-main-slide h2').addClass('animated fadeInUp').css('opacity', '1');
      $('.gauto-main-slide p').addClass('animated fadeInDown').css('opacity', '1');
      $('.gauto-main-slide .gauto-btn').addClass('animated fadeInDown').css('opacity', '1');
    });

    /* 
		=================================================================
		02 - Select JS
		=================================================================	
		*/

    $('select').niceSelect();

    /* 
		=================================================================
		03 - Clockpicker JS
		=================================================================	
		*/
    document.querySelectorAll('.flatpickr-date').forEach(element => {
      flatpickr(element, {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        monthSelectorType: 'static'
      });
    });

    /* 
		=================================================================
		04 - Service Slider JS
		=================================================================	
		*/

    $('.service-slider').owlCarousel({
      autoplay: true,
      loop: true,
      margin: 5,
      touchDrag: true,
      mouseDrag: true,
      items: 4,
      nav: false,
      dots: true,
      autoplayTimeout: 6000,
      autoplaySpeed: 1200,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      responsive: {
        0: { items: 1 },
        480: { items: 1 },
        600: { items: 2 },
        1000: { items: 3 },
        1200: { items: 4 } // <-- ab 4 show honge 1200px+ screen par
      }
    });

    /* 
		=================================================================
		05 - Testimonial Slider JS
		=================================================================	
		*/

    $('.testimonial-slider').owlCarousel({
      autoplay: true,
      loop: true,
      margin: 25,
      touchDrag: true,
      mouseDrag: true,
      items: 1,
      nav: false,
      dots: true,
      autoplayTimeout: 6000,
      autoplaySpeed: 1200,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      responsive: {
        0: {
          items: 1
        },
        480: {
          items: 1
        },
        600: {
          items: 1
        },
        1000: {
          items: 3
        },
        1200: {
          items: 3
        }
      }
    });

    /* 
		=================================================================
		06 - Responsive Menu JS
		=================================================================	
		*/
    $('ul#gauto_navigation').slicknav({
    prependTo: '.gauto-responsive-menu',
   
});

    /* 
		=================================================================
		07 - Back To Top
		=================================================================	
		*/
    if ($('body').length) {
      var btnUp = $('<div/>', {
        class: 'btntoTop'
      });
      btnUp.appendTo('body');
      $(document).on('click', '.btntoTop', function () {
        $('html, body').animate(
          {
            scrollTop: 0
          },
          700
        );
      });
      $(window).on('scroll', function () {
        if ($(this).scrollTop() > 200) $('.btntoTop').addClass('active');
        else $('.btntoTop').removeClass('active');
      });
    }

    /* 
		=================================================================
		08 - vehicle page toggle btn
		=================================================================	
		*/

    $('#searchButton').click(function () {
      $('#searchForm').toggle();
    });

    /* 
		=================================================================
		09 - preloader
		=================================================================	
		*/

    $(window).on('load', function () {
      let $preloader = $('#preloader');
      let $content = $('#content');

      $preloader.css({
        transition: 'opacity 0.5s ease-out',
        opacity: '0'
      });

      setTimeout(function () {
        $preloader.hide();
        $content.show();
      }, 500);
    });

    // home page serach

    function loadOptions(url, $select, placeholder, dependent = null) {
      $select.html(`<option value="">Loading...</option>`).niceSelect('update');

      $.ajax({
        url,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          Accept: 'application/json'
        },
        success: function (data) {
          let options = `<option value="">${placeholder}</option>`;
          data.forEach(item => {
            options += `<option value="${item.id}">${item.name}</option>`;
          });
          $select.html(options).niceSelect('update');
        }
      });
    }

    loadOptions('/frontend/locations', $('#locationSelect'), 'Select Location');
    loadOptions('/frontend/make', $('#makeSelect'), 'Select Make');
    loadOptions('/frontend/model', $('#modelSelect'), 'Select Model');

    $('#locationSelect').on('change', function () {
      let locationId = $(this).val();
      if (locationId) {
        loadOptions(`/frontend/makes/${locationId}`, $('#makeSelect'), 'Select Make');
      } else {
        $('#makeSelect').html('<option value="">Select Make</option>').niceSelect('update');
      }
    });

    $('#makeSelect').on('change', function () {
      let makeId = $(this).val();
      if (makeId) {
        loadOptions(`/models/${makeId}`, $('#modelSelect'), 'Select Model');
      } else {
        $('#modelSelect').html('<option value="">Select Model</option>').niceSelect('update');
      }
    });

    $('button[type="reset"]').on('click', function (e) {
      e.preventDefault();

      loadOptions('/frontend/locations', $('#locationSelect'), 'Select Location');
      loadOptions('/frontend/make', $('#makeSelect'), 'Select Make');
      loadOptions('/frontend/model', $('#modelSelect'), 'Select Model');
    });

    (function () {})();
  });

  $('#reservation-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: '/enquiry/request',
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        alert(response.message);
        $('#reservation-form')[0].reset();
      },
      error: function (xhr) {
        if (xhr.status === 422) {
          // Laravel validation error
          let errors = xhr.responseJSON.errors;
          let errorMessages = Object.values(errors).flat().join('\n');
          alert(errorMessages); // Show all validation errors
        } else {
          alert('Something went wrong. Please try again!');
        }
      }
    });
  });
})(jQuery);

document.addEventListener('DOMContentLoaded', function () {
  $(window).on('load', function () {
    let $preloader = $('#preloader');
    let $content = $('#content');

    $preloader.css({
      transition: 'opacity 0.5s ease-out',
      opacity: '0'
    });

    setTimeout(function () {
      $preloader.hide();
      $content.show();
    }, 500);
  });

  const lazyImages = document.querySelectorAll('img.lazy');

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        let img = entry.target;
        img.src = img.getAttribute('data-src');
        img.classList.remove('lazy');
        observer.unobserve(img);
      }
    });
  });

  lazyImages.forEach(img => observer.observe(img));
});
