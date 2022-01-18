const Toast = Swal.mixin({
  toast: true,
  position: "top",

  showConfirmButton: false,
  timer: 2000,
});
// General toast alert message according to type.
function alert_msg(type, msg) {
  Toast.fire({
    type: type,
    title: msg,
  });
}


// code to export all rows from datatable record
var oldExportAction = function (self, e, dt, button, config) {
  // if (button[0].className.indexOf('buttons-excel') >= 0) {
  //   if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
  //     $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
  //   }
  //   else {
  //     $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
  //   }
  // } else if (button[0].className.indexOf('buttons-print') >= 0) {
  //   $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
  // }
};

// var newExportAction = function (e, dt, button, config) {
//   var self = this;
//   var oldStart = dt.settings()[0]._iDisplayStart;

//   dt.one('preXhr', function (e, s, data) {
//     // Just this once, load all data from the server...
//     data.start = 0;
//     data.length = 2147483647;

//     dt.one('preDraw', function (e, settings) {
//       // Call the original action function 
//       oldExportAction(self, e, dt, button, config);
//       dt.one('preXhr', function (e, s, data) {
//         // DataTables thinks the first item displayed is index 0, but we're not drawing that.
//         // Set the property to what it was before exporting.
//         settings._iDisplayStart = oldStart;
//         data.start = oldStart;
//       });
//       // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
//       setTimeout(dt.ajax.reload, 0);
//       // Prevent rendering of the full data to the DOM
//       return false;
//     });
//   });

//   // Requery the server with the new one-time export settings
//   dt.ajax.reload();
// };


$("#firm_listing").DataTable({
  processing: true,
  serverSide: true,
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  fixedColumns: {
    leftColumns: 4,
  },
  serverMethod: "post",
  ajax: {
    url: admin_url + "firm/get_coach_data",
  },
  order: [[1, "asc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "firm_name" },
    { targets: 3, name: "address" },
    { targets: 4, name: "contactNumber" },
    { targets: 5, name: "date" },

  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
});


// confirmation before changing Coach status
$(document).on("click", ".change-coach-status", function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var status_val = $(this).data("status");
  console.log(id + "  " + status_val);
  let swal_text = "";

  if (status_val == "1") {
    swal_text = "You want to Activate this Coach";
  } else if (status_val == "0") {
    swal_text = "You want to Deactivate this Coach";
  }
  Swal.fire({
    title: "Are you sure?",
    text: swal_text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "firm/change_status",
        method: "post",
        data: { id: id, status: status_val },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});


// confirmation before deleting coach
$(document).on("click", "#delete-commodity", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this commodity.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "commodity/delete_commodity",
        method: "post",
        data: { id: id },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});

// date calendar for date of birth
if ($(".dob").val()) {
  var startDate = $(".dob").val();
}
else {
  var startDate = '02/03/1970';
}
var today = new Date();
var currentYear = today.getFullYear();
var minDate = new Date(1970, 0, +1); //one day next before month
var maxDate = new Date(currentYear - 15, 11, +31); // one day before next month
$(function () {
  $('input[name="dob"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minDate: minDate,
    maxDate: maxDate,
    autoUpdateInput: false,
    autoApply: true,
    startDate: startDate,
  }, function (start_date) {
    $(".dob").val(start_date.format("MM/DD/YYYY"));
  });
});


// code to list user in ajax datatable
$("#user_listing").DataTable({
  processing: true,
  serverSide: true,
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  fixedColumns: {
    leftColumns: 4,
  },
  serverMethod: "post",
  ajax: {
    url: admin_url + "user/get_user_data",
  },
  order: [[2, "asc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "usr.firstName" },
    { targets: 3, name: "usr.email" },
    { targets: 4, name: "usr.coFirm" },
    { targets: 5, name: "usr.created" },
  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
});



// confirmation before changing Coach status
$(document).on("click", ".change-user-status", function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var status_val = $(this).data("status");
  console.log(id + "  " + status_val);
  let swal_text = "";

  if (status_val == "1") {
    swal_text = "You want to Activate this User";
  } else if (status_val == "0") {
    swal_text = "You want to Deactivate this User";
  }
  Swal.fire({
    title: "Are you sure?",
    text: swal_text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "user/change_status",
        method: "post",
        data: { id: id, status: status_val },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});


// confirmation before deleting coach
$(document).on("click", "#delete-user", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this user.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "user/delete_user",
        method: "post",
        data: { id: id },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});




// code to list video in ajax datatable
$("#job_listing").DataTable({
  processing: true,
  serverSide: true,
  // dom:
  //   '<"row"<"col-xs-12 col-sm-4" <"top" l <" col-sm-6 mb-1" i> >><"col-xs-12 col-sm-8"f>>t<"row"<"col-sm-4"i><"col-sm-8"p>><"clear">',
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  dom: 'Bfrtip',
  fixedColumns: {
    leftColumns: 4,
  },
  serverMethod: "post",
  ajax: {
    url: admin_url + "job/get_video_data",
  },
  order: [[1, "asc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "j.purchaseOrder" },
    { targets: 3, name: "f.firm_name" },
    { targets: 4, name: "j.remaining_quantity" },
    { targets: 5, name: "j.dealValidUpto" },
    { targets: 6, name: "j.quanity" },
    { targets: 7, name: "j.commodity" },
    { targets: 8, name: "j.status" },
    // { targets: 9, name: "vd.created" },

  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
  buttons: [
    // {
    //   extend: 'excel',
    //   action: newExportAction
    // },
  ]
});



// confirmation before changing Coach status
$(document).on("click", ".change-video-status", function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var status_val = $(this).data("status");
  console.log(id + "  " + status_val);
  let swal_text = "";

  if (status_val == "1") {
    swal_text = "You want to Activate this Video";
  } else if (status_val == "0") {
    swal_text = "You want to Deactivate this Video";
  }
  Swal.fire({
    title: "Are you sure?",
    text: swal_text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "job/change_status",
        method: "post",
        data: { id: id, status: status_val },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});


// confirmation before deleting coach
$(document).on("click", "#delete-video", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  $.ajax({
    url: admin_url + "job/get_assignee",
    method: "post",
    data: { id: id },
    success: (data) => {

      $("#job_id").val(id);
      $("#assignToId").html(data);
      $("#job_modal").modal('show');
    },
  });
});



$(".custom-file-input").on("change", function () {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


// code to list user in ajax datatable
$("#commodity_listing").DataTable({
  processing: true,
  serverSide: true,
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  fixedColumns: {
    leftColumns: 4,
  },
  serverMethod: "post",
  ajax: {
    url: admin_url + "commodity/get_commodity_data",
  },
  order: [[2, "asc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "c.commodity" },
  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
});


// code to list entries in ajax datatable
$("#entries_list").DataTable({
  processing: true,
  serverSide: true,
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  fixedColumns: {
    leftColumns: 6,
  },
  serverMethod: "post",
  ajax: {
    url: admin_url + "entries/get_entries_data",
  },
  order: [[2, "desc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "firm.firm_name" },
    { targets: 3, name: "commodities.commodity" },
    // { targets: 4, name: "jobMeta.previousSlip" },
    // { targets: 5, name: "jobMeta.currentSlip" },
    // { targets: 6, name: "jobMeta.bill" },
    { targets: 4, name: "jobMeta.entryType" },
    { targets: 5, name: "jobMeta.deliveryType" },
    { targets: 6, name: "jobMeta.cNetWeight" },
    { targets: 7, name: "jobMeta.created" },
  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
});

// approve entry
$(document).on("click", "#approveEntry", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to approve this entry.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "entries/approveEntry",
        method: "post",
        data: { id: id },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});

// reject entry
$(document).on("click", "#rejectEntry", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to reject this entry.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "entries/rejectEntry",
        method: "post",
        data: { id: id },
        success: (data) => {
          location.reload();
        },
      });
    }
  });
});


// // code to list entries in ajax datatable
// $("#jobDetails_list").DataTable({
//   processing: true,
//   serverSide: true,
//   pageLength: 25,
//   scrollY: "calc(100vh - 250px)",
//   stateSave: true,
//   scrollX: true,
//   scrollCollapse: true,
//   fixedColumns: {
//     leftColumns: 6,
//   },
//   serverMethod: "post",
//   ajax: {
//     url: admin_url + "job/getJobEntries",
//   },
//   order: [[0, "desc"]],
//   columnDefs: [
//     { orderable: false, targets: [0] },
//     { targets: 4, name: "jobMeta.previousSlip" },
//     { targets: 5, name: "jobMeta.currentSlip" },
//     { targets: 6, name: "jobMeta.bill" },
//     { targets: 7, name: "jobMeta.entryType" },
//     { targets: 8, name: "jobMeta.deliveryType" },
//     { targets: 9, name: "jobMeta.created" },
//   ],
//   lengthMenu: [
//     [10, 25, 50, -1],
//     [10, 25, 50, "All"],
//   ],
// });

$(document).ready(function () {
  // Datapicker 
  $(".datepicker").datepicker({
    "dateFormat": "yy-mm-dd",
    changeYear: true
  });
});

// var from_date = "";
// var to_date = "";
// code to list entries in ajax datatable
var jobentry_datatable = $("#jobEntriesList").DataTable({
  processing: true,
  serverSide: true,
  pageLength: 25,
  scrollY: "calc(100vh - 250px)",
  stateSave: true,
  scrollX: true,
  scrollCollapse: true,
  fixedColumns: {
    leftColumns: 6,
  },
  serverMethod: "post",
  dom: 'Bfrtip',
  ajax: {
    url: admin_url + "Job/getJobEntries/" + $("#jobEntriesList").data("jobid"),
    'data': function (data) {
      // Read values
      var from_date = $('#search_fromdate').val();
      var to_date = $('#search_todate').val();

      // Append to data
      data.searchByFromdate = from_date;
      data.searchByTodate = to_date;
    }
  },
  order: [[2, "desc"]],
  columnDefs: [
    { orderable: false, targets: [0] },
    { targets: 1, name: "jobMeta.currentSlipNo" },
    { targets: 2, name: "jobMeta.previousSlip" },
    { targets: 3, name: "jobMeta.currentSlip" },
    { targets: 4, name: "jobMeta.bill" },
    { targets: 5, name: "jobMeta.qty" },
    { targets: 6, name: "jobMeta.deliveryType" },
    { targets: 7, name: "jobMeta.created" },
  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
  buttons: [
    // {
    //   extend: 'excel',
    //   action: newExportAction
    // },
  ]
});

// Search button
$('#btn_search').click(function () {
  console.log($('#search_fromdate').val());
  jobentry_datatable.draw();
});



// reject entry
$(document).on("click", "#showentrymodel", function (e) {
  e.preventDefault();

  let id = $(this).data("id");

  $.ajax({
    url: admin_url + "entries/getjobdetails",
    method: "post",
    data: {
      id: id
    },
    success: (data) => {
      // location.reload();
      $(".tbl_body").html(data);
    },
  });

});

// reject entry
$(document).on("click", "#approve", function (e) {
  e.preventDefault();

  let id = $(".entriesRadio:checked").data("entryid");
  let jobId = $(".entriesRadio:checked").data("jobid");
  let quantity = $(".entriesRadio:checked").data("quantity");
  let noOfBags = $(".entriesRadio:checked").data("quantitys");
  let netweight = $(".entriesRadio:checked").data("netweight");
  console.log(id);

  if (!jobId) {
    return;
  }

  Swal.fire({
    title: "Are you sure?",
    text: "You want to approve this entry.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8b0101",
    cancelButtonColor: "#000",
    confirmButtonText: "Yes",
  }).then(function (inputValue) {
    if (inputValue.value) {
      $.ajax({
        url: admin_url + "entries/approveEntry",
        method: "post",
        data: { id: id, jobId: jobId, quantity: quantity, noOfBags: noOfBags, netweight: netweight },
        success: (data) => {
          location.reload();
        },
      });
    }
  });

});

$(document).on("click", ".csvExportButton", function (e) {
  $.ajax({
    url: admin_url + "exportJobs",
    method: "post",
    data: {
      csv: true
    },
    success: (data) => {
      // location.reload();
    },
  });
});
