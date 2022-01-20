<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <form action="<?= base_url('admin/exportAllEntries'); ?>" method="post" id="add_job" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect3">Filter By Firm</label>
                    <select class="form-control" name="bFirm" id="bFirm">
                        <option value="">Select Firm</option>
                        <?php
                        if (!empty($firm_list)) {
                            foreach ($firm_list as $list) {
                        ?>
                                <option value="<?= $list->id ?>"><?= $list->firm_name ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect3">Filter By Commodity</label>
                    <select class="form-control" name="bCommodity" id="bCommodity">
                        <option value="">Select Commodity</option>
                        <?php
                        if (!empty($commodities)) {
                            foreach ($commodities as $list) {
                        ?>
                                <option value="<?= $list->id ?>"><?= $list->commodity ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect3">Filter By Status</label>
                    <select class="form-control" name="bStatus" id="bStatus">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </div>
            <label for="exampleFormControlSelect2">Filter By Date</label>
            <hr />
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Date From</label>
                    <input type="date" class="form-control" id="bSelectedDate" name="bSelectedDate">
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Date To</label>
                    <input type="date" class="form-control" id="bSelectedDateTo" name="bSelectedDateTo">
                </div>
            </div>
            <label for="exampleFormControlSelect2">Filter By Purchase Order</label>
            <hr />
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">PO From</label>
                    <select class="form-control" name="poFrom" id="poFrom">
                        <option value="">Select Purchase Order</option>
                        <?php
                        if (!empty($purchaseOrders)) {
                            foreach ($purchaseOrders as $list) {
                        ?>
                                <option value="<?= $list->purchaseOrder ?>"><?= $list->purchaseOrder ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">PO To</label>
                    <select class="form-control" name="poTo" id="poTo">
                        <option value="">Select Purchase Order</option>
                        <?php
                        if (!empty($purchaseOrders)) {
                            foreach ($purchaseOrders as $list) {
                        ?>
                                <option value="<?= $list->purchaseOrder ?>"><?= $list->purchaseOrder ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/job'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat ml-1" title="Add">Export</button>
            </div>
        </form>
    </div>
    <!-- content-wrapper ends -->