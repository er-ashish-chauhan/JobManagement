<?php
//  pr($data,1);
$firm_name = "";
$address = "";
$id='';

if (isset($data) && !empty($data)) {
    $firm_name = $data->firm_name;
    $address = $data->address;
    $id = $data->id;
}

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <form action="<?= base_url('admin/firm/manage_firm_detail'); ?>" method="post" id="edit_firm" enctype="multipart/form-data">

            <input type="hidden" name="id" id="id" value="<?= $id ?>">

            <div class="form-group">

                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="firstName">Name <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="firm_name" name="firm_name" value="<?= $firm_name ?>" >
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="lastName">Address <span class="astric-sign">*</span></label>
                        <input type="text" class="form-control" maxlength="35" id="address" name="address" value="<?= $address ?>" >
                    </div>
                </div>
               
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/firm'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="<?= $btn_name ?>"><?= $btn_name ?></button>
            </div>

        </form>




    </div>
    <!-- content-wrapper ends -->