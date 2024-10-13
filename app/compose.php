<?php
ini_set("max-excecution", 999);
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
require_once 'vendor/autoload.php';
$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

if ($role != "admin") {
    header("location:login");
}


$unread_msg = mysqli_num_rows(mysqli_query($conn, "select * from contact where status='no'"));
$read_msg = mysqli_num_rows(mysqli_query($conn, "select * from contact where status='yes'"));
$inbox = mysqli_num_rows(mysqli_query($conn, "select * from contact"));
$reply_status = isset($_GET["reply"]) ? $_GET["reply"] : "";


$contact_id = isset($_SESSION["contact_id"]) ? $_SESSION["contact_id"] : "";
$fullname = isset($_SESSION["fullname"]) ? $_SESSION["fullname"] : "";
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "";
$status = isset($_SESSION["status"]) ? $_SESSION["status"] : "";
$date = isset($_SESSION["date"]) ? $_SESSION["date"] : "";
$message = isset($_SESSION["message"]) ? $_SESSION["message"] : "";
$title = isset($_SESSION["title"]) ? $_SESSION["title"] : "";

$error_message = [];
$success_message = [];
if (isset($_POST["send"])) {
    $subject = $_POST["subject"];
    $text = $_POST["message"];
    $email = $_POST["email"];
    $status = "yes";
    if ($reply_status == "yes") {
        $contact_id = $_SESSION["contact_id"];
    } else {
        $contact_id = "mail";
    }
    $date = date("Y-m-d H:i:s");


    try {
        $receiver = trim($email);
        $subject = $subject;

        $body = "<html><head><title>Account Authentication</title></head><body><table style='text-align:justify; padding: 20px; border-radius:30%;'>";
        $body .= "<tr bgcolor='#d4af37'><td><h2 style='color:#000000; text-align:center !important;'>social media application</h2></td></tr>";
        $body .= "<tr bgcolor='#0f5f4f'><td><p style='color:#ffffff !important; padding:10px; text-align:justify !important;'>$text</p></td></tr>";
        $body .= "<tr bgcolor='#d4af37' style='color:#000000; '><td><p style='color:#000000; text-align:center; padding:10px;'>Kind Regards <br>PGE Team!</p></td></tr>";
        $body .= "</table></body></html>";
        // Create the Transport
        $api_user_name = "";
        $api_password = "";
        $transport = Swift_SmtpTransport::newInstance('smtp.mailgun.org', 587)
                ->setUsername($api_user_name)
                ->setPassword($api_password)
        ;

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($subject)
                ->setFrom(array('fatima@gmail.com' => 'social media team'))
                ->setTo(array(trim($email) => $fullname))
                ->setContentType('text/html')
                ->setBody($body);

        ;

        // Send the message
        $result = $mailer->send($message);

        if ($result) {
            $inser_query = mysqli_query($conn, "insert into emails values('','$email','$subject','$text','$status','$contact_id','$date')");
            if ($inser_query) {
                array_push($success_message, "Email sent successfully");
            } else {
                array_push($error_message, "Operations Failed, please try after some minutes");
            }
        } else {
            array_push($error_message, "Poor Network, Unable to send email, please try after some minutes");
        }
    } catch (Exception $ex) {
        //$alert = 'Registration unsuccessfull, ensure your email is valid and try again';
        $alert = $ex->getMessage();
        array_push($error_message, $alert);
    }
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


        <title><?php echo $sitename; ?> | Compose</title>

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
                                    <a href="feedback" class="btn btn-rounded btn-success btn-block">Back to Inbox</a>
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



                                <!-- /. box -->

                                <!-- /.box -->	
                            </div>				
                        </div>
                    </div>
                </section>
                <!-- /.left content -->

                <!-- right content -->
                <section class="right-block content">
                    <form method="post" action="">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Compose New Message</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <?php
                                    if ($reply_status == "yes") {
                                        ?>
                                        <div class="form-group">
                                            <input type="email" value="<?php echo $email; ?>" name="email" class="form-control" placeholder="To:"/>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="form-group">
                                            <input type="email" value="" name="email" class="form-control" placeholder="To:"/>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="subject"  placeholder="Subject:">
                                </div>
                                <div class="form-group">
                                    <textarea id="compose-textarea" name="message" class="form-control" style="height: 300px">
						  <p>Your Message Here....</p>
                                    </textarea>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-rounded btn-default"><i class="fa fa-pencil"></i> Draft</button>
                                    <button type="submit" name="send" class="btn btn-rounded btn-success"><i class="fa fa-envelope-o"></i> Send</button>
                                </div>
                                <button type="reset" class="btn btn-rounded btn-danger"><i class="fa fa-times"></i> Discard</button>
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
        <script src="../assets/vendor_plugins/iCheck/icheck.js"></script>
        <script src="../assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script src="js/pages/form-compose.js"></script>

        <!-- Famosa Admin App -->
        <script src="js/template.js"></script>

    </body>

</html>
