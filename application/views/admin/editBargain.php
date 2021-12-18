<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <form action="<?= base_url('admin/editBargain/' . $jobId); ?>" method="post" id="add_job" enctype="multipart/form-data">

            <div class="row">
                <!-- <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Job Name</label>
                    <input type="text" class="form-control" maxlength="35" id="job_name" name="job_name">
                </div> -->
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect3">Select Firm</label>
                    <select class="form-control" name="firmId" id="firmId" disabled>
                        <option value="">Select Firm</option>
                        <?php
                        if (!empty($firm_list)) {
                            foreach ($firm_list as $list) {
                        ?>
                                <option value="<?= $list->id ?>" <?php echo $list->id == $bargain->firmId ? "selected" : "" ?>><?= $list->firm_name ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-4 col-xs-12">
                    <label for="exampleFormControlSelect2">Total Quantity</label>
                    <input type="text" class="form-control" maxlength="5" id="total_quantity" name="total_quantity" min="0" value="<?php echo $bargain->total_quantity ?>">
                </div>
                <div class="form-group col-sm-2 col-xs-12">
                    <label for="exampleFormControlSelect3">Quantity Type</label>
                    <select class="form-control" name="qtyTpe" id="qtyTpe">
                        <option value="">Select Qty Type</option>
                        <option value="qts" <?php echo $bargain->quantityType == "qts" ? "selected" : "" ?>>QTS</option>
                        <option value="bags" <?php echo $bargain->quantityType == "bags" ? "selected" : "" ?>>Bags</option>
                        <option value="trucks" <?php echo $bargain->quantityType == "trucks" ? "selected" : "" ?>>Trucks</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Price</label>
                    <input type="number" class="form-control" maxlength="5" id="qtyPrice" name="qtyPrice" min="0" value="<?php echo $bargain->price ?>" disabled>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Broker Name</label>
                    <input type="text" class="form-control" id="broker_name" name="broker_name" value="<?php echo $bargain->brokerName ?>" disabled>
                </div>
            </div>
            <div class="row">

                <div class="form-group col-sm-3 col-xs-12">
                    <label for="exampleFormControlSelect3">Select Commodity</label>
                    <select class="form-control" name="commodityId" id="commodityId" disabled>
                        <option value="">Select Commodity</option>
                        <?php
                        if (!empty($commodities)) {
                            foreach ($commodities as $list) {
                        ?>
                                <option value="<?= $list->id ?>" <?php echo $list->id == $bargain->commodityId ? "selected" : "" ?>><?= $list->commodity ?></option>

                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-3 col-xs-12">
                    <label for="exampleFormControlSelect3">Delivery Type</label>
                    <select class="form-control" name="deliveryType" id="deliveryType" disabled>
                        <option value="">Select Delivery Type</option>
                        <option value="Ex-Mill" <?php echo $bargain->deliveryType == "Ex-Mill" ? "selected" : "" ?>>Ex-Mill</option>
                        <option value="FOR" <?php echo $bargain->deliveryType == "FOR" ? "selected" : "" ?>>FOR</option>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Deal valid from</label>
                    <input type="date" class="form-control" id="dealvalidFrom" name="dealvalidFrom" value="<?php echo $bargain->dealValidFrom ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Deal valid upto</label>
                    <input type="date" class="form-control" id="dealvalid" name="dealvalid" value="<?php echo $bargain->dealValidUpto ?>">
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/job'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="Add">Update</button>
            </div>
        </form>
    </div>
    <!-- content-wrapper ends -->