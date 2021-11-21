<?php
//  pr($data,1);
$vProfilePic = "";
$dob = "";
$gender="";

if (isset($data) && !empty($data)) {
    // pr($data); die;
    $vProfilePic = USER_IMAGE_URL . $data->profileImage;
    // $dob = $data->dob;
    $dob = date("m/d/Y", strtotime($data->dob));
    $gender = $data->gender;
}

// pr($this->session->userdata()['is_admin']['id'],1);

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <form action="<?= base_url('admin/user/manage_user_detail'); ?>" method="post" id="edit_coach" enctype="multipart/form-data">

            <input type="hidden" name="id" id="id" value="<?= $data->id ?? "" ?>">

            <div class="form-group">

                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="firstName">First Name <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="firstName" name="firstName" value="<?= $data->firstName ?? "" ?>" placeholder="Full Name">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="lastName">Last Name <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="lastName" name="lastName" value="<?= $data->lastName ?? "" ?>" placeholder="Last Name">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="email">Username <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="70" id="email" value="<?= $data->email ?? "" ?>" name="email" placeholder="Email">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="password">Password<span class="astric-sign">*</span></label>
                        <input type="password" class="form-control" maxlength="128" id="password" name="password" value="<?= $data->password ?? "" ?>" placeholder="Username">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/user'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="Update">Add</button>
            </div>

        </form>




    </div>
    <!-- content-wrapper ends -->