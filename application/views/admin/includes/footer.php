
            <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

<?php
  $this->load->view('admin/includes/admin_script');
?>
</body>
</html>

<?php
    $success_msg=$this->session->flashdata('success');
    $error_msg=$this->session->flashdata('error');
    if(!empty($success_msg))
    {
        ?>
<script>
$(function() {
    alert_msg('success', "<?=$success_msg?>");
});
</script>
<?php
    } 
    if(!empty($error_msg))
    {
        ?>
<script>
$(function() {
    alert_msg('error', "<?=$error_msg?>");
});
</script>

<?php
    }
?>


