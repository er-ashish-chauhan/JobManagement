<?php
//  pr($data,1);
$vProfilePic = "";
$dob = "";
$gender="";

if (isset($data) && !empty($data)) {
    // pr($data); die;
    $vProfilePic = ADMIN_IMAGE_URL . $data->profileImage;
}

// pr($this->session->userdata()['is_admin']['id'],1);

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <form action="<?= base_url('admin/profile/manage_profile_detail'); ?>" method="post" id="edit_profile" enctype="multipart/form-data">

            <div class="row">
                <div class="form-group col-sm-3 col-xs-3 d-flex flex-wrap mangae-adm-pr">

                    <div class="admin-prf-img">
                        <img src="<?= $vProfilePic ?>" id="uploaded_vProfilePic" height="100px" width="150px" alt="Profile Image">
                    </div> 
                </div>
                <div class="form-group col-sm-9 col-xs-9 d-flex flex-wrap mangae-adm-pr">
                <label>Upload Profile <span class="astric-sign">*</span></label>
                    <div class="input-group col-xs-12">
                        <input type="file" class="form-control custom-file-input" id="customFile" name="profileImage" accept="image/*" >
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
            </div>

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
                        <label for="email">Email <span class="astric-sign">*</span></label>
                        <input type="email" class="form-control" maxlength="70" id="email" value="<?= $data->email ?? "" ?>" name="email" placeholder="Email">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="password">Password<span class="astric-sign">*</span></label>
                        <input type="password" class="form-control" maxlength="128" id="password" name="password" value="<?= $data->password ?? "" ?>" placeholder="Username">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="contact">Mobile Number <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="70" value="<?= $data->contact ?? "" ?>" name="contact" placeholder="Mobile Number">
                        <?php echo form_error('contact'); ?>
                    </div>
                   
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/user'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="Update">Update</button>
            </div>

        </form>




    </div>
    <!-- content-wrapper ends -->