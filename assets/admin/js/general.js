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


$("#coach_listing").DataTable({
  processing: true,
  serverSide: true,
  // "dom": '<"top"lif>rt<"row"<"col-sm-4"i><"col-sm-8"p>><"clear">',
  // dom:
  //   '<"row"<"col-xs-12 col-sm-4" <"top" l <" col-sm-6 mb-1" i> >><"col-xs-12 col-sm-8"f>>t<"row"<"col-sm-4"i><"col-sm-8"p>><"clear">',
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
    { orderable: false, targets: [0] },
    { targets: 1, name: "firm_name" },
    { targets: 2, name: "address" },
    // { targets: 4, name: "usr.contact" },
    // { targets: 6, name: "usr.dob" },
    // { targets: 9, name: "usr.created" },

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
    { orderable: false, targets: [0] },
    { targets: 1, name: "usr.firstName" },
    { targets: 2, name: "usr.email" },
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
$("#video_listing").DataTable({
  processing: true,
  serverSide: true,
  // dom:
  //   '<"row"<"col-xs-12 col-sm-4" <"top" l <" col-sm-6 mb-1" i> >><"col-xs-12 col-sm-8"f>>t<"row"<"col-sm-4"i><"col-sm-8"p>><"clear">',
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
    url: admin_url + "job/get_video_data",
  },
  order: [[1, "asc"]],
  columnDefs: [
    { orderable: false, targets: [0, 1] },
    { targets: 2, name: "j.job_name" },
    { targets: 3, name: "f.firm_name" },
    { targets: 4, name: "u.firstname" },
    // { targets: 6, name: "vd.title" },
    // { targets: 8, name: "vd.views" },
    // { targets: 9, name: "vd.created" },

  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
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
    { targets: 4, name: "jobMeta.previousSlip" },
    { targets: 5, name: "jobMeta.currentSlip" },
    { targets: 6, name: "jobMeta.bill" },
    { targets: 7, name: "jobMeta.entryType" },
    { targets: 8, name: "jobMeta.deliveryType" },
    { targets: 9, name: "jobMeta.created" },
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

// code to list entries in ajax datatable
$("#jobEntriesList").DataTable({
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
    url: admin_url + "Job/getJobEntries/" + $("#jobEntriesList").data("jobid"),
  },
  order: [[2, "desc"]],
  columnDefs: [
    { orderable: false, targets: [0] },
    { targets: 1, name: "jobMeta.previousSlip" },
    { targets: 2, name: "jobMeta.currentSlip" },
    { targets: 3, name: "jobMeta.bill" },
    { targets: 4, name: "jobMeta.entryType" },
    { targets: 5, name: "jobMeta.deliveryType" },
    { targets: 6, name: "jobMeta.created" },
  ],
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "All"],
  ],
});