<style>
    img.entryImage {
        width: 90px;
        height: 70px;
        border-radius: 10px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-md-12">
                <h3 style="display:inline;" class="card-title"><?= $pageTitle = "Job Entries" ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="exportBtnContainer" style="margin-bottom: 20px;margin-left: 10px;">
                                <a href="<?php echo base_url("admin/exportEntries/" . encode($jobDetails->id)); ?>" class="csv_ExportButton btn btn-primary">Export as CSV</a>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <span>Purchase Order: - <?php echo $jobDetails->purchaseOrder ?></span><br>
                                        <span>Broker Name: - <?php echo $jobDetails->brokerName != "" ? $jobDetails->brokerName : "-" ?></span><br>
                                    </div>
                                    <div class="col-6">
                                        <span>Total Quantity: - <?php echo $jobDetails->total_quantity . " " . $jobDetails->quantityType ?></span><br>
                                        <span>Remaing Quantity: - <?php echo $jobDetails->remaining_quantity . " " . $jobDetails->quantityType ?></span>
                                    </div>
                                </div>
                                <!-- <br>
                                <br>
                                <div class="row">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">From</label>
                                        <input type='text' id='search_fromdate' class="datepicker" placeholder='From date' autocomplete="off">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">To</label>
                                        <input type='text' id='search_todate' class="datepicker" placeholder='To date' autocomplete="off">
                                    </div>
                                    <input type='button' id="btn_search" value="Search" style="margin-top:20px;">

                                </div> -->
                                <div class="table-responsive">
                                    <table id="jobEntriesList" data-jobid="<?php echo decode($jobId) ?>" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Actions</th>
                                                <th>Current Slip No</th>
                                                <th>Party</th>
                                                <th>Commodity</th>
                                                <th>Quantity</th>
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

    <!-- modal  -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).on('click', '.previous_img', function() {
            console.log("img clicked");
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
        });
    </script>