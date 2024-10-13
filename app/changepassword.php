<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

$alert = "";
if (isset($_POST["submit"])) {
    $op = md5(secure($conn, $_POST["op"]));
    $np = md5(secure($conn, $_POST["np"]));
    $cp = md5(secure($conn, $_POST["cp"]));

    $get_curr_pass = mysqli_query($conn, "select * from login where username='$userid'") or die(mysqli_error($conn));
    $curr_pass = mysqli_fetch_array($get_curr_pass);
    $pass = $curr_pass["password"];
    if ($op == $pass) {
        if ($np == $cp) {
            $update_pass = mysqli_query($conn, "update login set password='$np' where username='$userid'") or die(mysqli_error($conn));
            if ($update_pass) {
                // echo "<script>alert('password changed successfully')</script>";
                $alert = "password changed successfully";
            }
        } else {
            $alert = "new password doesnt match confirm password!";
            //echo "<script>alert('new password doesnt match confirm password!')</script>";
        }
    } else {
        $alert = "incorrect old password, please try after some minute";
        //echo "<script>alert('incorrect old password, please try after some minute')</script>";
    }
}
?>

﻿<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="favicon.png" sizes="16x16">


        <title><?php echo $sitename; ?></title>

        <!-- Vendors Style-->
        <link rel="stylesheet" href="css/vendors_css.css">

        <!-- Style-->  
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/skin_color.css">

    </head>

    <body class="hold-transition light-skin sidebar-mini theme-primary">

        <div class="wrapper">

            <?php
            include 'includes/header.php';
            ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?php
            include 'includes/admin_sidebar.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="container-full">
                    <!-- Content Header (Page header) -->	  
                    <div class="content-header">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <p style="color:yellowgreen;" class="text-center h3"><?php echo $alert; ?></p>
                                <h3 class="page-title br-0">Change Password</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">

                            <div class="col-lg-12 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label>Old Password</label>
                                                <input name="op" type="password" required="" class="form-control" placeholder="Old Password">
                                            </div>
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input name="np" type="password" required="" class="form-control" placeholder="New Password">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input name="cp" type="password" required=""  class="form-control" placeholder="Confirm Password">
                                            </div>

                                            <div class="form-group">
                                                <input type="submit" class="form-control btn btn-primary" name="submit" value="submit">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </section>
                    <!-- /.content -->
                </div>
            </div>
            <!-- /.content-wrapper -->
            <?php
            include 'includes/footer.php';
            ?>
            <!-- Control Sidebar -->

            <!-- /.control-sidebar -->

            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->


        <!-- Vendor JS -->
        <script src="js/vendors.min.js"></script>

        <script src="../assets/vendor_components/apexcharts-bundle/irregular-data-series.js"></script>
        <script src="../assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
        <script src="../assets/vendor_components/echarts/dist/echarts-en.min.js"></script>

        <!-- Famosa Admin App -->
        <script src="js/template.js"></script>
        <script src="js/pages/dashboard.js"></script>


    </body>

    <!-- Mirrored from html.psdtohtmlexpert.com/admin/famosa-admin/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 23 Dec 2020 08:41:47 GMT -->
</html>
