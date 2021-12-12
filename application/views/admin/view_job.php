<style>
    img.entryImage {
        width: 130px;
        height: 110px;
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
                            <div class="col-12">
                                <div class="col-12">
                                    <span>Purchase Order: - <?php echo $jobDetails->purchaseOrder ?></span><br>
                                    <span>Broker Name: - <?php echo $jobDetails->brokerName != "" ? $jobDetails->brokerName : "-" ?></span><br>
                                    <span>Total Quantity: - <?php echo $jobDetails->total_quantity." ". $jobDetails->quantityType?></span>
                                </div>
                                <br>
                                <br>
                                <div class="table-responsive">
                                    <table id="jobEntriesList" data-jobid="<?php echo decode($jobId) ?>" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Previous Slip</th>
                                                <th>Current Slip</th>
                                                <th>Bill</th>
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