<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
  .navbar .fa-bell, 
  .navbar .ml-3,
  .navbar .fa-facebook-messenger { /* Add styling for the Messenger icon */
        color: white;
  }
  .dropdown-menu {
  width: 50px; /* Adjust the width as needed */
}

.dropdown-item {
  white-space: nowrap; /* Prevents text from wrapping to the next line */
  overflow: hidden; /* Ensures that overflowing content is hidden */
  text-overflow: ellipsis; /* Adds "..." at the end of overflowing text */
}
</style>
<!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-light border-top-0  border-left-0 border-right-0 text-sm shadow-sm bg-gradient-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url ?>" class="nav-link"><b><?php echo (!isMobileDevice()) ? $_settings->info('name'):$_settings->info('short_name'); ?> - Admin</b></a>
          </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
         <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messenger Icon -->
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="fab fa-facebook-messenger"></i>
      </a>
    </li>

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

    <!-- User Profile Dropdown -->
    <li class="nav-item">
      <div class="btn-group nav-link">
        <button type="button" class="btn btn-rounded badge badge-red btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
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

        <!-- Add this script if FontAwesome is not already included -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// AJAX to mark notifications as read when clicked
document.getElementById('notificationDropdown').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "mark_notifications_read.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Do something if needed (like refreshing the notification count or dropdown)
        }
    };
    xhr.send(); // Send AJAX request to update notifications
});
</script>
