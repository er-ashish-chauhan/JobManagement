<?php

$entryType = "";
$deliveryType = "";
$commodity = "";
$firm_name = "";
$cNetWeight = "";
$bill ="";
$previousSlip = "";
$currentSlip = "";
$id = "";

if(!empty($entry_details))
{
    $entryType = $entry_details->entryType;
    $deliveryType = $entry_details->deliveryType;
    $commodity = $entry_details->commodity;
    $firm_name = $entry_details->firm_name;
    $cNetWeight = $entry_details->cNetWeight;
    $id = $entry_details->id;
    //images
    $bill = $entry_details->bill;
    $previousSlip = $entry_details->previousSlip;
    $currentSlip = $entry_details->currentSlip;
}
?>

<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <form action="<?= base_url('admin/entries/edit_entries_detail'); ?>" method="post" id=""
      enctype="multipart/form-data">

      <div class="row">
        <input type="hidden" name="job_meta_id" value="<?= $id ?? "" ?>">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect3">Select Firm</label>
          <select class="form-control" name="firmId" id="firmId">
            <option value="">Select Firm</option>
            <?php
                 if ( isset($firm_list) && !empty($firm_list)) {
                            foreach ($firm_list as $list) {
                        ?>
            <option value="<?= $list->id ?>" <?php echo $list->id == $entries->firmId ? "selected" : "" ?>>
              <?= $list->firm_name ?></option>
            <?php
                            }
                        }
                        ?>
          </select>
        </div>
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect3">Select Commodity</label>
          <select class="form-control" name="commodityId" id="commodityId">
            <option value="">Select Commodity</option>
            <?php
                        if ( isset($commodities) && !empty($commodities)) {
                            foreach ($commodities as $list) {
                        ?>
            <option value="<?= $list->id ?>" <?php echo $list->id == $entries->commodityId ? "selected" : "" ?>>
              <?= $list->commodity ?></option>
            <?php
                            }
                        }
                        ?>
          </select>
        </div>

      </div>
      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Entry Type</label>
          <input type="text" class="form-control" maxlength="5" id="entryType" name="entryType" min="0"
            value="<?= $entryType ?>">
        </div>
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect3">Delivery Type</label>
          <input type="text" class="form-control" maxlength="5" id="deliveryType" name="deliveryType"
            value="<?= $deliveryType ?>">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Gross weight</label>
          <input type="text" class="form-control" maxlength="5" id="entryType" name="entryType" min="0"
            value="<?= $entryType ?>">
        </div>
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect3">Tare weight</label>
          <input type="text" class="form-control" maxlength="5" id="deliveryType" name="deliveryType"
            value="<?= $deliveryType ?>">
        </div>
      </div>


      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Net Weight</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">No of Bags</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Net Weight</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">No of Bags</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>
      </div>


      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Choose Bill</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="inputGroupFile01"
                aria-describedby="inputGroupFileAddon01" name="bill">
              <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Bill No</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>
      </div>


      <div class="row">

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Previous Slip</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="inputGroupFile01"
                aria-describedby="inputGroupFileAddon01" name="previousSlip">
              <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Previous Slip No</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>

      </div>

      <div class="row">
        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Current Slip</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="inputGroupFile01"
                aria-describedby="inputGroupFileAddon01" name="currentSlip">
              <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label for="exampleFormControlSelect2">Current Slip No</label>
          <input type="text" class="form-control" maxlength="5" id="cNetWeight" name="cNetWeight"
            value="<?= $cNetWeight ?>">
        </div>


      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <a href="<?= base_url('admin/entries'); ?>" class="btn btn-warning step-back float-left admin-cancel-btn"
          title="Cancel">Back</a>
        <button type="submit" class="btn btn-success btn-flat ml-1" title="Add">Update</button>
      </div>
    </form>
  </div>
  <!-- content-wrapper ends -->