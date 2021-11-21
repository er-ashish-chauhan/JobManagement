      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <!-- 
            <li class="nav-item" >
            <a class="nav-link" href="<?= base_url('admin/user/123') ?>">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/firm') ?>">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage Coach</span>
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/user') ?>">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage User</span>
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/job') ?>">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage Video</span>
            </a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Jobs</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item" > <a class="nav-link" href="<?= base_url('admin/job/manage_job_detail?action=add'); ?>">Add Job</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/job') ?>">Job's Listing</a></li>
              </ul>
            </div>
          </li>
         
          
          
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
              <i class="icon-contract menu-icon"></i>
              <span class="menu-title">Manage User</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/user/manage_user_detail'); ?>">Add User</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/user') ?>">User's List</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Manage Firm</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/firm/manage_coach_detail?action=add'); ?>"> Add Firm </a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/firm') ?>"> Firm's List </a></li>
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
              <i class="icon-ban menu-icon"></i>
              <span class="menu-title">Error pages</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="error">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
              </ul>
            </div>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" href="pages/documentation/documentation.html">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Documentation</span>
            </a>
          </li> -->
        </ul>
      </nav>