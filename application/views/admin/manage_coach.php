<?php
//  pr($data,1);
$vProfilePic = "";
$dob = "";

if (isset($data) && !empty($data)) {
    $vProfilePic = COACH_IMAGE_URL . $data->profileImage;
    // $dob = $data->dob;
    $dob = date("m/d/Y", strtotime($data->dob));
}

// pr($this->session->userdata()['is_admin']['id'],1);

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <form action="<?= base_url('admin/firm/manage_coach_detail'); ?>" method="post" id="edit_coach" enctype="multipart/form-data">

            <input type="hidden" name="id" id="id" value="<?= $data->id ?? "" ?>">

            <div class="form-group">

                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="firstName">Name <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="firm_name" name="firm_name" >
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="lastName">Address <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="address" name="address" >
                    </div>
                </div>
              
               
               
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/user'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="<?= $btn_name ?>"><?= $btn_name ?></button>
            </div>

        </form>




    </div>
    <!-- content-wrapper ends -->