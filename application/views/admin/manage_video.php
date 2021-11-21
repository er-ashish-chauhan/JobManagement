<?php
//  pr($data,1);
// $vProfilePic="";
// $dob="";
$coach_id="";
$category_id="";
$title="";
$shortDescription="";
$longDescription="";

if(isset($data) && !empty($data))
{
    // $vProfilePic = COACH_IMAGE_URL.$data->profileImage;
    $coach_id=$data->coachId;
    $category_id=$data->categoryId;
    $title=$data->title;
    $shortDescription=$data->shortDescription;
    $longDescription=$data->longDescription;
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
                    <input type="text" class="form-control" maxlength="35" id="job_name" name="job_name">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect3">Select User</label>
                    <select class="form-control" name="firmId" id="firmId">
                      <option value="">--User--</option>
                      <?php 
                        if(!empty($firm_list))
                        {
                            foreach($firm_list as $list)
                            {
                               ?>
                      <option value="<?= $list->id ?>" <?= $category_id == $list->id ? 'selected':'' ?> ><?= $list->firm_name ?></option>

                               <?php
                            }
                        }
                      ?>
                    </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Total Quantity</label>
                    <input type="text" class="form-control" maxlength="5" id="total_quantity" name="total_quantity">
                    </div>
                   
                </div>
               
            <!-- </div> -->
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/job'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="Add">Add</button>
            </div>
        </form>




    </div>
    <!-- content-wrapper ends -->