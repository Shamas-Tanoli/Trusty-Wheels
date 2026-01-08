/**
 * App user list (jquery)
 */

'use strict';

$(function () {
  $('.datatables-permissions').DataTable({ 
    processing: true,
    serverSide: true,
    ajax: '/dashboard/service/list',
    searchDelay: 1000,
    columns: [
      // { data: 'id', name: 'id' },
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'name', name: 'name', orderable: false, searchable: true },
      { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
      { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ],
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    dom:
      '<"row mx-1"' +
      '<"col-sm-12 col-md-3" l>' +
      '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap"<"me-4 mt-n6 mt-md-0"f>B>>' +
      '>t' +
      '<"row"' +
      '<"col-sm-12 col-md-6"i>' +
      '<"col-sm-12 col-md-6"p>' +
      '>',
    language: {
      sLengthMenu: 'Show _MENU_',
      search: '',
      searchPlaceholder: 'Search Service',
      paginate: {
        next: '<i class="ti ti-chevron-right ti-sm"></i>',
        previous: '<i class="ti ti-chevron-left ti-sm"></i>'
      }
    },
    buttons: [
      {
        text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Service</span>',
        className: 'add-new btn btn-primary mb-6 mb-md-0 waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addPermissionModal'
        },
        init: function (api, node, config) {
          $(node).removeClass('btn-secondary');
        }
      }
    ]
  });
});
