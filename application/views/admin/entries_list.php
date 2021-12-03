<style>
    button.btn.btn-success.btn-flat.ml-1.comm_submit_btn {
        margin-top: 29px;
    }

    img.entryImage {
        width: 130px;
        height: 110px;
        border-radius: 10px;
    }
</style>


<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-md-12">
                <h3 style="display:inline;" class="card-title"><?= $pageTitle = "Manage Entries" ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="entries_list" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Firm</th>
                                                <th>Commodity</th>
                                                <th>Previous Slip</th>
                                                <th>Current Slip</th>
                                                <th>Bill Slip</th>
                                                <th>Entry Type</th>
                                                <th>Delivery Type</th>
                                                <th>Created</th>
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