<?php
$job_name = "";
$firm_name = "";
$fullname = "";
$quantityConfirmed = "";
$image = "";

if (isset($data) && !empty($data)) {
    // $vProfilePic = COACH_IMAGE_URL.$data->profileImage;
    $job_name = $data->job_name;
    $firm_name = $data->firm_name;
    $fullname = $data->fullname;
    $quantityConfirmed = $data->quantityConfirmed;
    $image = $data->image;
}
?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <form action="<?= base_url('admin/job/manage_job_detail'); ?>" method="post" id="edit_video" enctype="multipart/form-data">

            <!-- <div class="form-group"> -->
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Job Name</label>
                    <input type="text" class="form-control" maxlength="35" id="job_name" name="job_name" value="<?= $job_name ?>">
                </div>

                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Firm Name</label>
                    <input type="text" class="form-control" maxlength="35" id="firm_name" name="firm_name" value="<?= $firm_name ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Assignee</label>
                    <input type="text" class="form-control" maxlength="35" id="fullname" name="fullname" value="<?= $fullname ?>">
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Confirmed Quantity</label>
                    <input type="text" class="form-control" maxlength="5" id="quantityConfirmed" name="quantityConfirmed">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Image</label>
                    <img src="<?= JOB_IMAGE_URL.$image ?>" class="img-thumbnail" alt="Cinque Terre" width="300" >
                </div>
            </div>

            <!-- </div> -->
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/job'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Back</a>
            </div>
        </form>




    </div>
    <!-- content-wrapper ends -->