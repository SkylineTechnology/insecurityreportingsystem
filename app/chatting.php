<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
include 'phpInsight-master/autoload.php';


$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";
$deptid = isset($_SESSION["dept_id"]) ? $_SESSION["dept_id"] : "";
$chatting_id = isset($_GET["chat"]) ? base64_decode($_GET["chat"]) : "";

if (!$_SESSION) {
    header("location:login");
}

//sender of message
$row = mysqli_fetch_array(mysqli_query($conn, "select * from members where email ='$userid'"));
$sender_id = $row["memberid"];
$sender_fullname = $row["fullname"];
$sender_email = $row["email"];
$sender_gender = $row["gender"];
$sender_dept_id = $row["dept_id"];
$sender_level = $row["level"];

//receiver of messgae
$rw1 = mysqli_fetch_array(mysqli_query($conn, "select * from members where email ='$chatting_id'"));
$recepient_id = $rw1["memberid"];
$recepient_fullname = $rw1["fullname"];
$recepient_email = $rw1["email"];
$recepient_gender = $rw1["gender"];
$recepient_dept_id = $rw1["dept_id"];
$recepient_level = $rw1["level"];

if (isset($_POST["submit"])) {
    $comment_msg = htmlentities(addslashes($_POST["message"]));

    if (trim($comment_msg) != "") {
        $sentiment = new \PHPInsight\Sentiment();
        $category = $sentiment->categorise($comment_msg);

        if ($category == "pos") {
            $cat = "positive";
        } elseif ($category == "neu") {
            $cat = "neutral";
        } elseif ($category == "neg") {
            $cat = "negative";
        }


        $sender = $userid;
        $receiver = $chatting_id;
        $date = date("Y-m-d H:i:s");
        $cid = "CHAT-" . date("YmdHis");
        $status = 'pending';

        $mssg = mysqli_query($conn, "insert into chat values ('$cid','$sender','$receiver','$comment_msg','$status','$cat','$date')") or die(mysqli_error($conn));
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
            include 'includes/sidebar.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="container-full">
                    <!-- Content Header (Page header) -->	  
                    <div class="content-header">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h3 class="page-title">Chat Menu</h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">Chat Menu</li>
                                            <li class="breadcrumb-item active" aria-current="page">Chat app</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>  

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="box">
                                    <div class="box-header with-border p-0">
                                        <div class="form-element lookup">
                                            <input class="form-control p-20 w-p100" type="text" placeholder="Search Contact">
                                        </div>
                                    </div>
                                    <div class="box-body p-0">
                                        <div id="chat-contact" class="media-list media-list-hover media-list-divided ">
                                            <?php
                                            $get_dept_members = mysqli_query($conn, "select * from members where dept_id='$deptid'");
                                            while ($row = mysqli_fetch_array($get_dept_members)) {
                                                $member_id = $row["memberid"];
                                                $fullname = $row["fullname"];
                                                $email = $row["email"];
                                                $gender = $row["gender"];
                                                $level = $row["level"];
                                                $reg_date = date_format(date_create($row["reg_date"]), "d, M Y H:i");
                                                if ($email == $userid) {
                                                    continue;
                                                }
                                                ?>
                                                <div class="media media-single">
                                                    <a href="chatting?chat='<?php echo base64_encode($email) ?>'" class="avatar avatar-lg status-success">
                                                        <img src="../images/icon2.jpg" alt="...">
                                                    </a>

                                                    <div class="media-body">
                                                        <h6><a href="chating?m='<?php echo base64_encode($email) ?>'"><?php echo $fullname; ?></a></h6>
                                                        <small class="text-green">Online</small>
                                                    </div>
                                                </div>   
                                                <?php
                                            }
                                            ?>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="box direct-chat">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Chat Message</h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <!-- Conversations are loaded here -->
                                        <div id="chat-app" class="direct-chat-messages chat-app">
                                            <!-- Message. Default to the left -->
                                            <div class="direct-chat-msg mb-30">
                                                <?php
                                                $result = mysqli_query($conn, "select * from chat where (sender='$userid' and receiver='$chatting_id') or (receiver='$userid' and sender='$chatting_id') order by date asc");
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $sender_id = $row["sender"];
                                                    $receiver_id = $row["receiver"];
                                                    $sent_message = $row["msg"];
                                                    $msg_date = date_format(date_create($row["date"]), " M d, Y H:iA");
                                                    $status = $row["status"];
                                                    $sentiment = $row["sentiment"];

                                                    if ($sender_id == $userid) {
                                                        ?>
                                                        <div class="clearfix mb-15">
                                                            <span class="direct-chat-name"><?php echo $sender_fullname; ?></span>
                                                            <span class="direct-chat-timestamp pull-right"><?php echo $msg_date; ?></span>
                                                        </div>
                                                        <!-- /.direct-chat-info -->
                                                        <img class="direct-chat-img avatar" src="../images/icon.png" alt="message user image">
                                                        <!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text">
                                                            <p><?php echo $sent_message; ?></p>
                                                            <p style="color: #0062cc;" class="direct-chat-timestamp">SP: <time datetime="2018"><?php echo $sentiment; ?></time></p>
                                                        </div>
                                                        <hr>
                                                        <!-- /.direct-chat-text -->
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="clearfix mb-15">
                                                            <span class="direct-chat-name"><?php echo $recepient_fullname; ?></span>
                                                            <span class="direct-chat-timestamp pull-right"><?php echo $msg_date; ?></span>
                                                        </div>
                                                        <!-- /.direct-chat-info -->
                                                        <img class="direct-chat-img ml-10 avatar" src="../images/icon2.jpg" alt="message user image">
                                                        <!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text ">
                                                            <p><?php echo $sent_message; ?></p>
                                                            <p style="color: #0062cc;" class="direct-chat-timestamp">SP: <time datetime="2018"><?php echo $sentiment; ?></time></p>
                                                        </div>
                                                        <hr>
                                                        <!-- /.direct-chat-text -->

                                                        <!-- /.direct-chat-text -->
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>


                                        <div class = "box-footer">
                                            <form action = "" method = "post">
                                                <div class = "input-group">
                                                    <input type = "text" name = "message" placeholder = "Type Message ..." class = "form-control">
                                                    <div class = "input-group-addon">
                                                        <div class = "align-self-end gap-items">

                                                            <button type="submit" name="submit">
                                                                <i class = "fa fa-paper-plane"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                    <!--/.direct-chat-messages-->
                                </div>
                                <!--/.box-body -->

                                <!--/.box-footer-->
                            </div>
                        </div>
                </div>
                </section>
                <!--/.content -->
            </div>
        </div>
        <!--/.content-wrapper -->
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
