      <!-- partial -->
      <div class="main-panel">
          <div class="content-wrapper">
              <div class="row">
                  <div class="col-md-12">
                      <h4 style="display:inline;" class="card-title"><?= $pageTitle = "Manage Job" ?></h4>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                      <div class="card">
                          <div class="card-body">
                              <div class="exportBtnContainer" style="margin-bottom: 20px;">
                                  <!-- <a href="<?php echo base_url("admin/exportJobs"); ?>" class="csv_ExportButton btn btn-primary">Export Bargain as CSV</a> -->
                                  <a href="<?php echo base_url("admin/applyFilters"); ?>" class="csv_ExportButton btn btn-primary">Export Entries as PDF</a>
                                  <!-- <a href="<?php echo base_url("admin/exportAllEntries"); ?>" class="csv_ExportButton btn btn-primary">Export Entries as PDF</a> -->
                              </div>
                              <div class="row">
                                  <div class="col-12">

                                      <div class=" table-responsive">
                                          <table id="job_listing" class="display expandable-table" style="width:100%">
                                              <thead>
                                                  <tr>
                                                      <th>#</th>
                                                      <th>Action</th>
                                                      <th>Purchase Order</th>
                                                      <th>Party Name</th>
                                                      <th>Remaining Quantity</th>
                                                      <th>Valid upto</th>
                                                      <th>Quantity</th>
                                                      <th>Commodity</th>
                                                      <th>Status</th>
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


          <div class="modal fade" id="job_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Assign Job</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <form action="<?= base_url('admin/job/assign_job'); ?>" method="post" id="edit_video" enctype="multipart/form-data">

                              <input type="hidden" name="id" id="job_id">
                              <!-- <div class="form-group"> -->
                              <div class="row">
                                  <div class="form-group col-sm-6 col-xs-12">
                                      <label for="exampleFormControlSelect3">Select User</label>
                                      <select class="form-control" name="assignToId" id="assignToId">

                                      </select>
                                  </div>
                              </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Assign</button>
                      </div>
                      </form>
                  </div>
              </div>
          </div>