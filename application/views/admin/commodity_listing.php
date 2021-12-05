    <style>
        button.btn.btn-success.btn-flat.ml-1.comm_submit_btn {
            margin-top: 29px;
        }
    </style>

<?php
$commodity='';
$id='';

if(isset($data) && !empty(($data)))
{
    $commodity = $data->commodity;
    $id = $data->id;
}

?>
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <h3 style="display:inline;" class="card-title"><?= $pageTitle = "Manage Commodity" ?></h3>
                </div>
            </div>
            <form action="<?= base_url('admin/Commodity/submitCommodity'); ?>" method="post" name="submitCommodity" id="submitCommodity">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                <input type="hidden" name="id" value="<?= $id ?>" >
                                    <!-- <div class="col-md-12"> -->
                                    <div class="form-group col-sm-6 col-xs-12">
                                        <label for="firstName">Commodity <span class="astric-sign">*</span></label>
                                        <input type="text" class="form-control" maxlength="35" id="commodity" name="commodity" value="<?= $commodity ?>" >
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <button type="submit" class="btn btn-success btn-flat ml-1 comm_submit_btn" name="add_commodity" value="add" title="add"><?= $btn_name ?></button>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="commodity_listing" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Action</th>
                                                    <th>Commodity</th>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->