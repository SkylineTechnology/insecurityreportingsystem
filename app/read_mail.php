<?php
session_start();
include 'includes/connection.php';
$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

if ($role != "admin") {
    header("location:login");
}

$unread_msg = mysqli_num_rows(mysqli_query($conn, "select * from contact where status='no'"));
$read_msg = mysqli_num_rows(mysqli_query($conn, "select * from contact where status='yes'"));
$inbox = mysqli_num_rows(mysqli_query($conn, "select * from contact"));
$cont_id = isset($_GET["cid"]) ? base64_decode($_GET["cid"]) : "";

$row = mysqli_fetch_array(mysqli_query($conn, "select * from contact where contact_id='$cont_id'")) or die(mysqli_error($conn));
$contact_id = $row["contact_id"];
$fullname = $row["fullname"];
$email = $row["email"];
$status = $row["status"];
$date = $row["date"];
$message = $row["message"];
$title = $row["title"];

if ($status == "no") {
    mysqli_query($conn, "update contact set status='yes' where contact_id='$contact_id'") or die(mysqli_error($conn));
}

$error_message = [];
$success_message = [];

if (isset($_POST["delete"])) {
    $del_query = mysqli_query($conn, "delete from contact where contact_id='$contact_id'");
    if ($del_query) {
        array_push($success_message, "Message(s) deleted successfully");
        header("location:feedback");
    } else {
        array_push($error_message, "Operations Failed, please try after some minutes");
    }
}

if (isset($_POST["reply"])) {
    $_SESSION["contact_id"] = $contact_id;
    $_SESSION["fullname"] = $fullname;
    $_SESSION["email"] = $email;
    $_SESSION["status"] = $status;
    $_SESSION["date"] = $date;
    $_SESSION["message"] = $message;
    $_SESSION["title"] = $title;
    header("location:compose?reply=yes");
}

//for success messages
foreach ($success_message as $key => $value) {
    if ($key < 1) {
        echo "<script>alert('$value')</script>";
    } else {
        break;
    }
}


//for error messages
foreach ($error_message as $kay => $val) {
    if ($kay < 1) {
        echo "<script>alert('$val')</script>";
    } else {
        break;
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


        <title><?php echo $sitename; ?> | Read Mail</title>

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
            include 'includes/sidebar.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- left content -->
                <section class="left-block content">
                    <a class="mdi mdi-close mdi-menu btn btn-success open-left-block d-block d-md-none" href="javascript:void(0)"></a>
                    <div class="scrollable" style="height: 100%;">
                        <div class="left-content-area">
                            <div class="h-p100">

                                <div class="p-20">
                                    <a href="compose" class="btn btn-rounded btn-success btn-block">Compose</a>
                                </div>

                                <div class="box mb-0 no-shadow bg-transparent b-0">
                                    <div class="box-header with-border p-20">
                                        <h4 class="box-title">Folders</h4>
                                        <small><?php echo $unread_msg; ?> new messages</small>
                                    </div>
                                    <div class="box-body mailbox-nav p-20">
                                        <ul class="nav nav-pills flex-column">
                                            <li class="nav-item"><a class="nav-link active" href="feedback"><i class="ion ion-ios-email-outline"></i> Inbox
                                                    <span class="label label-success pull-right"><?php echo $inbox; ?></span></a></li>
                                            <li class="nav-item"><a class="nav-link" href="feedback?msg=read"><i class="ion ion-paper-airplane"></i> Read
                                                    <span class="label label-success pull-right"><?php echo $read_msg; ?></span></a></li>
                                            <li class="nav-item"><a class="nav-link" href="feedback?msg=unread"><i class="ion ion-email-unread"></i> Unread
                                                    <span class="label label-success pull-right"><?php echo $unread_msg; ?></span></a></li>
                                        </ul>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                            </div>				
                        </div>
                    </div>
                </section>
                <!-- /.left content -->

                <!-- right content -->
                <section class="right-block content">
                    <form action="" method="post">
                        <div class="box">
                            <div class="box-body" id="dataTable">
                                <div class="mailbox-controls with-border clearfix mt-15">                
                                    <div class="float-left">
                                        <a href="javascript:printtab()">   <button type="button" class="btn btn-outline btn-sm" data-toggle="tooltip" title="Print">
                                                <i class="fa fa-print"></i></button></a>
                                    </div>
                                    <div class="float-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
                                                <i class="fa fa-trash-o"></i></button>
                                            <button type="button" class="btn btn-outline btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
                                                <i class="fa fa-reply"></i></button>
                                            <button type="button" class="btn btn-outline btn-sm" data-toggle="tooltip" data-container="body" title="Forward">
                                                <i class="fa fa-share"></i></button>
                                        </div>
                                    </div>
                                    <!-- /.btn-group -->

                                </div>
                                <!-- /.mailbox-controls -->
                                <div class="mailbox-read-info">
                                    <h3><?php echo $title; ?></h3>
                                </div>
                                <div class="mailbox-read-info bb-0 clearfix">
                                    <div class="float-left mr-5"><a href="#"><img src="favicon.png" alt="user" width="40" class="rounded-circle"></a></div>
                                    <h5 class="no-margin">
                                        <small>From: <?php echo $email; ?></small>
                                        <span class="mailbox-read-time pull-right"><?php echo date_format(date_create($date), " d M Y H:i A "); ?></span></h5>
                                </div>
                                <!-- /.mailbox-read-info -->
                                <div class="mailbox-read-message">
                                    <p style="text-align: justify"><?php echo $message; ?></p>
                                </div>
                                <!-- /.mailbox-read-message -->
                            </div>
                            <!-- /.box-body -->

                            <!-- /.box-footer -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" name="reply" class="btn btn-rounded btn-success"><i class="fa fa-reply"></i> Reply</button>
                                    <button type="button" name="reply" class="btn btn-rounded btn-info"><i class="fa fa-share"></i> Forward</button>
                                </div>
                                <button type="submit" onclick="return confirm('Comfirm you want to delete this message')" name="delete" class="btn btn-rounded btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
                                <button type="button" class="btn btn-rounded btn-warning"><i class="fa fa-print"></i><a href="javascript:printtab()"> Print</a></button>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </form>
                    <!-- /. box -->

                </section>
                <!-- /.right content -->
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

        <script src="../assets/vendor_components/perfect-scrollbar-master/perfect-scrollbar.jquery.min.js"></script>

        <!-- Famosa Admin App -->
        <script src="js/template.js"></script>

        <script type="text/javascript">
                                //ptinting function
                                function printtab() {
                                    var tab = document.getElementById('dataTable');
                                    newwin = window.open("");
                                    newwin.document.write(tab.outerHTML);
                                    newwin.print();
                                    newwin.close();
                                }
        </script>
    </body>

</html>
