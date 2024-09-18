<style>
  .user-img {
    position: absolute;
    height: 27px;
    width: 27px;
    object-fit: cover;
    left: -7%;
    top: -12%;
  }
  
  .btn-rounded {
    border-radius: 50px;
  }

  .navbar .fa-bell,
  .navbar .ml-3,
  .navbar .fa-facebook-messenger { 
    color: white;
  }

  .dropdown-menu {
    width: 300px; /* Adjust width as needed */
  }

  .dropdown-item {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .nav-link .fas {
    color: white;
  }
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light bg-gradient-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?php echo base_url ?>" class="nav-link"><b><?php echo (!isMobileDevice()) ? $_settings->info('name') : $_settings->info('short_name'); ?> </b></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notification Bell -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" id="notificationDropdown">
        <i class="fas fa-bell"></i>
        <span class="badge badge-warning navbar-badge"><?php echo $notificationCount; ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"><?php echo $notificationCount; ?> Notifications</span>
        <div class="dropdown-divider"></div>
        <?php while ($notification = $notificationsResult->fetch_assoc()): ?>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> <?php echo htmlspecialchars($notification['content']); ?>
            <span class="float-right text-muted text-sm"><?php echo date('M d, Y h:i A', strtotime($notification['date'])); ?></span>
          </a>
          <div class="dropdown-divider"></div>
        <?php endwhile; ?>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- Messenger Icon -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-facebook-messenger"></i>
        <span class="badge badge-primary navbar-badge">3</span> <!-- Example unread messages -->
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">3 Messages</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> New message from John Doe
          <span class="float-right text-muted text-sm">5 mins ago</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>

    <!-- User Profile Dropdown -->
    <li class="nav-item">
      <div class="btn-group nav-link">
        <button type="button" class="btn btn-rounded badge badge-light btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
          <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
          <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname')) ?></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
          <a class="dropdown-item" href="<?php echo base_url . 'admin/?page=user' ?>"><span class="fa fa-user"></span> My Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url . '/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
        </div>
      </div>
    </li>
  </ul>
</nav>

<script>
  // Mark notifications as read when clicked
  document.getElementById('notificationDropdown').addEventListener('click', function() {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "mark_notifications_read.php", true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
              // Refresh or update notification count if necessary
          }
      };
      xhr.send(); 
  });
</script>
