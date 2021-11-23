<?php
$job_name = "";
$firm_name = "";
$fullname = "";
$quantityConfirmed = "";
$image = "";

// if (isset($data) && !empty($data)) {
//     // $vProfilePic = COACH_IMAGE_URL.$data->profileImage;
//     $job_name = $data->job_name;
//     $quantityConfirmed = $data->quantityConfirmed;
//     $image = $data->image;
// }
?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-md-12">
                <h4 style="display:inline;" class="card-title"><?= $pageTitle = "Job Details" ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="jobDetails_list" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Job Name</th>
                                                <th>Total Quantity</th>
                                                <th>Quantity Confirmed</th>
                                                <th>Image</th>
                                                <th>Created</th>
                                            </tr>
                                            <?php
                                            $sn_count = 1;
                                            foreach ($data as $value) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $sn_count ?></td>
                                                    <td><?php echo $value->job_name ?></td>
                                                    <td><?php echo $value->total_quantity ?></td>
                                                    <td><?php echo $value->quantityConfirmed ?></td>
                                                    <td>
                                                        <image src=<?php echo str_replace("JobManagement/", "", base_url()) . str_replace("/var/www/html/", "", $value->image) ?> width="80" height="80" />
                                                    </td>
                                                    <td><?php echo $value->created ?></td>

                                                </tr>
                                            <?php
                                                $sn_count++;
                                            }
                                            ?>
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