<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <form action="<?= base_url('admin/addBroker'); ?>" method="post" id="add_job" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="exampleFormControlSelect2">Broker Name</label>
                    <input type="text" class="form-control" id="broker_name" name="broker_name" placeholder="Enter Broker Name">
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a href="<?= base_url('admin/brokerslist'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn" title="Cancel">Cancel</a>
                <button type="submit" name="submit" class="btn btn-success btn-flat ml-1" title="Add">Add Broker</button>
            </div>
        </form>
    </div>
    <!-- content-wrapper ends -->