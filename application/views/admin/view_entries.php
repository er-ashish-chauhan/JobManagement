<?php
//  echo "<pre>";
//  print_r($entry_details); die;

$entryType = "";
$deliveryType = "";
$commodity = "";
$firm_name = "";
$cNetWeight = "";
$bill = "";
$previousSlip = "";
$currentSlip = "";
$id = "";

if (!empty($entry_details)) {
    $entryType = $entry_details->entryType;
    $deliveryType = $entry_details->deliveryType;
    $commodity = $entry_details->commodity;
    $firm_name = $entry_details->firm_name;
    $cNetWeight = $entry_details->cNetWeight;
    $id = $entry_details->id;
    //images
    $bill = $entry_details->bill;
    $previousSlip = $entry_details->previousSlip;
    $currentSlip = $entry_details->currentSlip;
}

?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <form action="<?= base_url('admin/entries/manage_bargain_detail'); ?>" method="post" id="add_job" enctype="multipart/form-data">

            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <h4>Firm Name: </h4><?= $firm_name ?>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <h4>Commodity: </h4><?= $commodity ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <h4>Entry type:</h4> <?= $entryType ?>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <h4>Delivery type:</h4> <?= $deliveryType ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <h4>Net-weight:</h4> <?= $cNetWeight ?>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-12">
                    <h4>Previous-Slip:</h4>
                    <a class='previous_img' data-imageurl=<?= str_replace("JobManagement/", "", base_url()) . $previousSlip ?> href='javascript:void(0)'>
                        <img src="<?= str_replace("JobManagement/", "", base_url()) . $previousSlip ?>" alt="previous-slip" style="height: 150px;width: 150px;">
                    </a>
                </div>
                <div class="form-group col-sm-4 col-xs-12">
                    <h4>Current-Slip</h4>
                    <a class='previous_img' data-imageurl=<?= str_replace("JobManagement/", "", base_url()) . $currentSlip ?> href='javascript:void(0)'>
                        <img src="<?= str_replace("JobManagement/", "", base_url()) . $currentSlip ?>" alt="current-slip" style="height: 150px;width: 150px;">
                    </a>
                </div>
                <div class="form-group col-sm-4 col-xs-12">
                    <h4>Bill-Image:</h4>
                    <a class='previous_img' data-imageurl=<?= str_replace("JobManagement/", "", base_url()) . $bill ?> href='javascript:void(0)'>
                        <img src="<?= str_replace("JobManagement/", "", base_url()) . $bill ?>" alt="Bill-img" style="height: 150px;width: 150px;">
                    </a>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/entries'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Back</a>
                <a href="<?= base_url('admin/entries/edit_entries_detail?id=') . encode($id) ?>" class="btn btn-success btn-flat ml-1" title="Cancel">Edit details</a>

            </div>
        </form>
    </div>
    <!-- content-wrapper ends -->
      <!-- modal  -->
      <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).on('click', '.previous_img', function() {
            console.log("img clicked");
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
        });
    </script>