<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once('inc/header.php') ?>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/your/custom/styles.css"> <!-- Optional: Your custom CSS -->
    <style>
    .user-img {
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
        color: blue;
    }
    .btn-rounded {
        border-radius: 50px;
    }
    .navbar .fa-bell,
    .navbar .ml-3 {
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
    .nav-link .fas {
        color: black;
    }
    .content-wrapper {
        background-image: url('nha.jpg'); /* Set your image path */
        background-size: cover; /* Cover the entire area */
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
    }
</style>

</head>
<body class="layout-fixed control-sidebar-slide-open layout-navbar-fixed">
    <div class="wrapper">
        <?php require_once('inc/topBarNav.php') ?>
        <?php require_once('inc/navigation.php') ?>

        <?php if($_settings->chk_flashdata('success')): ?>
        <script>
            alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
        </script>
        <?php endif;?>    

        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper pt-3">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <?php 
                            if(!file_exists($page.".php") && !is_dir($page)){
                                include '404.html';
                            } else {
                                if(is_dir($page))
                                    include $page.'/index.php';
                                else
                                    include $page.'.php';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->

            <!-- Modals -->
            <div class="modal fade" id="confirm_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmation</h5>
                        </div>
                        <div class="modal-body">
                            <div id="delete_content"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-flat" id='confirm'>Continue</button>
                            <button type="button" class="btn btn-default border btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="uni_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-flat" id='submit'>Save</button>
                            <button type="button" class="btn btn-default border btn-flat" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="uni_modal_right" role='dialog'>
                <div class="modal-dialog modal-full-height modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-arrow-right"></span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="viewer_modal" role='dialog'>
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <?php require_once('inc/footer.php') ?>
    </div>
    <!-- /.wrapper -->

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Sidebar navigation functionality
        $(document).ready(function () {
            $('.nav-link').on('click', function() {
                if ($('.sidebar').hasClass('open')) {
                    $('.sidebar').removeClass('open');
                    // Trigger a pushmenu toggle
                    $('.navbar .nav-link[data-widget="pushmenu"]').click();
                }
            });

            // AJAX to mark notifications as read when clicked
            document.getElementById('notificationDropdown').addEventListener('click', function() {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "mark_notifications_read.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.querySelector('.navbar-badge').textContent = 0; // Reset the notification count
                    }
                };
                xhr.send(); // Send AJAX request to update notifications
            });
        });
    </script>
</body>
</html>
