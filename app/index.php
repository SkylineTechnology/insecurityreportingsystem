<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
include 'phpInsight-master/autoload.php';


$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";
$deptid = isset($_SESSION["dept_id"]) ? $_SESSION["dept_id"] : "";

if (!$_SESSION) {
    header("location:../index");
}


//commenting on post
if (isset($_POST["addcomment"])) {
    $comment_msg = htmlentities(addslashes($_POST["comment"]));

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

        $comm_id = "POST-" . date("Ymisdh");
        $comm_date = date("Y-m-d H:i:s");
        $postid = $_POST["postid"];
        $insert_commnet = mysqli_query($conn, "insert into comment values ('$comm_id','$postid','$userid','$comment_msg','$cat','$comm_date')");
        if ($insert_commnet) {
            echo "<script>alert('comment successfully added')</script>";
        } else {
            echo "<script>alert('Oops! looks like we are having a downtime, please try after some minutes')</script>";
        }
    } else {
        echo "<script>alert('comment field can not be empty')</script>";
    }
}

//creating post
if (isset($_POST["add"])) {
    $post_msg = htmlentities(addslashes($_POST["msg"]));
    $post_id = "POST-" . date("Ymisdh");
    $post_date = date("Y-m-d H:i:s");

    if($_FILES['image']['error'] === 4){
        echo "<script> alert('Image Does Not Exist') </script>";
    }else{
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $tmpName = $_FILES['image']['tmp_name'];
        
        $validImageExtension = ['jpg', 'jpeg', 'png', 'jfif'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        
        if(!in_array($imageExtension, $validImageExtension)){
            echo "<script> alert('Invalid Image Extension') </script>";
        }else if($fileSize > 1000000){
            echo "<script> alert('Image Size is too Large') </script>";
        }else{
            $newImageName = uniqid('',true).".".$imageExtension;
            //$newImageName += '.'.$imageExtension;
            
            move_uploaded_file($tmpName, 'img/'.$newImageName);

        
        $insert_post = mysqli_query($conn, "insert into complaints values ('$post_id','$userid','$post_msg','$newImageName','Unseen','$post_date')");
        if ($insert_post) {
            echo "<script>alert('Your Complaint have send successfully')</script>";
        } else {
            echo "<script>alert('Oops! looks like we are having a downtime, please try after some minutes')</script>";
        }
    }
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
                                <h3 class="page-title">Report</h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">My Timeline</li>
                                            <li class="breadcrumb-item active" aria-current="page">Post</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <section class="content">
                        
                            <div class="publisher publisher-multi col-md-12 bg-white b-1 mb-30">
                                <form method="post" action="" enctype="multipart/form-data">
                                    <textarea  name="msg" class="publisher-input col-md-12 auto-expand" rows="4" placeholder="Write something"></textarea>
                                    <input type="file" name="image" class="publisher-input col-md-12 auto-expand">
                                    <div class="flexbox">
                                        <div class="gap-items">

                                            </span>
                                            <a class="publisher-btn" href="#"><i class="fa fa-map-marker"></i></a>
                                            <a class="publisher-btn" href="#"><i class="fa fa-smile-o"></i></a>
                                        </div>

                                        <button type="submit" name="add" class="btn btn-sm btn-bold btn-primary">Post</button>
                                    </div>
                                </form>

                            </div>
                            <?php
                            //Start Pagination 
                            $item_per_page = 4;
                            $number_of_items = mysqli_num_rows(mysqli_query($conn, "select * from complaints  order by date desc"));
                            $number_of_pages = ceil($number_of_items / $item_per_page);

                            if (!isset($_GET['page'])) {
                                $page = 1;
                            } else {
                                $page = $_GET['page'];
                            }
                            $current_page_first_item = ($page - 1) * $item_per_page;
                            //End Pagination
                            //online post from users of thesame department can be seen here
                            $get_post = mysqli_query($conn, "select * from complaints");
                            while ($row = mysqli_fetch_array($get_post)) {
                                $postid = $row["post_id"];
                                $username = $row["r_id"];
                                $message = $row["complaints"];
                                $img = $row['image'];
                                $compl_status = $row["status"];
                                $date = gatDates($row["date"]);

                                //getting user records
                                $rows1 = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email='$username'"));
                                $member_id = $rows1["r_id"];
                                $fullname = $rows1["fullname"];
                                $email = $rows1["email"];
                                $gender = $rows1["gender"];
                               // $level = $row1["level"];
                                $reg_date = date_format(date_create($rows1["reg_date"]), "d, M Y H:i");


                                

                                //getting number of posted comment with their various polarity
                                $num_of_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid'"));
                                $num_pos_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='positive'"));
                                $num_neg_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='negative'"));
                                $num_neu_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='nuetral'"));
                                ?>
                                
                                    <div class="media bb-1 border-fade">
                                        <img class="avatar avatar-lg" src="../images/icon.png" alt="...">
                                        <div class="media-body">
                                            <p>
                                                <strong><?php echo $fullname ?></strong>
                                                <time class="float-right text-fade" datetime="2017"><?php echo $date; ?></time>
                                            </p>
                                            <p><small></small></p>
                                        </div>
                                    </div>

                                    
                                        <p class="lead" style="margin:45px;"><?php echo $message; ?><img style="width:200px; height:200px; float:right;" src="img/<?php echo $img; ?>"></p>

                                        <div class="gap-items-4 mt-10" style="margin-bottom:120px;">
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-thumbs-up mr-1"></i> <?php echo $num_pos_comments; ?>
                                            </a>
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-comment mr-1"></i> <?php echo $num_of_comments; ?>
                                            </a>
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-thumbs-down mr-3"></i> <?php echo $num_neg_comments; ?> 
                                            </a>
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-comment mr-1"></i><b style="color:blueviolet;"><?php echo $compl_status; ?></b>
                                            </a>
                                        </div>
                                    


                                    <div class="media-list media-list-divided bg-lighter">

                                        <?php
                                        $get_post_comments = mysqli_query($conn, "select * from comment where postid='$postid' order by date desc");
                                        while ($row2 = mysqli_fetch_array($get_post_comments)) {
                                            $comment_id = $row2["comid"];
                                            $comment_postid = $row2["postid"];
                                            $comment_username = $row2["username"];
                                            $comment_message = $row2["msg"];
                                            $comment_sentiment = $row2["sentiment"];
                                            $comment_date = gatDates($row2["date"]);

                                            //getting commenter user records
                                            $row1 = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email='$comment_username'"));
                                            $comment_member_id = $row1["r_id"];
                                            $comment_fullname = $row1["fullname"];
                                            $comment_email = $row1["email"];
                                            $comment_gender = $row1["gender"];
                                            //$comment_level = $row1["level"];
                                            $comment_reg_date = date_format(date_create($row1["reg_date"]), "d, M Y H:i");
                                            ?>
                                            
                                            <div class="media">
                                                <a class="avatar" href="#">
                                                    <img src="../images/icon2.jpg" alt="...">
                                                </a>
                                                <div class="media-body">
                                                    <p>
                                                        <a href="#"><strong><?php echo $comment_fullname; ?></strong></a>

                                                        <time class="float-right text-fade" datetime="2017-07-14 20:00"><?php echo $comment_date; ?></time>
                                                        <a class="text-fade small mr-3 float-right hover-light" href="#">
                                                            <i class="fa fa-comment mr-1"></i>SP: <b style="color:blueviolet;"></b>
                                                        </a>
                                                    </p>
                                                    <p><?php echo $comment_message ?>.</p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                        
                                    <form method="post" action="" class="publisher bt-1 border-fade">
                                        <img class="avatar avatar-sm" src="../images/icon2.jpg" alt="...">
                                        <input class="publisher-input" name="comment" type="text" placeholder="Add Your Comment">
                                        <input name="postid" type="hidden" value="<?php echo $postid; ?>">
                                        <button type="submit" class="btn btn-sm btn-bold btn-primary" name="addcomment"> <i class="fa fa-arrow-circle-up"></i></button>

                                    </form><br><br><br><br>

                                
                                <?php
                            }
                            ?>
                            <div class="row justify-content-center title">
                            <?php
                            if (isset($_GET['page']) && $_GET['page'] != "") {
                                //Pagination display
                                ?>
                                <div class="inner_sec_grids_info_w3ls tab-content tab_grid_prof">
                                    <nav >
                                        <ul class="pagination tab-content team-card border-radius-100 pagination pagination-lg">
                                            <?php
                                            $nav = isset($_GET['page']) ? $_GET['page'] : "1";
                                            if ($nav > 1) {
                                                $prev = $nav - 1;
                                                ?>
                                            <li><a href="index?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            //Page navigation
                                            for ($page = 1; $page <= $number_of_pages; $page++) {
                                                if (isset($_GET['page']) && $_GET['page'] == $page) {
                                                    $active = 'active';
                                                } elseif (!isset($_GET['page']) && $page == 1) {
                                                    $active = 'active';
                                                } else {
                                                    $active = "";
                                                }
                                                ?>
                                                <li class="<?php echo $active; ?>"><a href="index?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if ($number_of_pages > $nav) {
                                                $next = $nav + 1;
                                                ?>
                                                <li><a href="index?page=<?php echo $next; ?>">Next &raquo;</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                </div>
                                <?php
                            } else {
                                //Normal pagination display
                                ?>
                                <div class="inner_sec_grids_info_w3ls tab-content tab_grid_prof">
                                    <nav >
                                        <ul class="pagination team-card border-radius-100  pagination-lg">
                                            <?php
                                            $nav = isset($_GET['page']) ? $_GET['page'] : "1";
                                            if ($nav > 1) {
                                                $prev = $nav - 1;
                                                ?>
                                                <li><a href="index?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
                                                <?php
                                            } else {
                                                ?>
                                                <li class="disabled"><a href="#">&laquo; Previous</a></li>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            //Page navigation
                                            for ($page = 1; $page <= $number_of_pages; $page++) {
                                                if (isset($_GET['page']) && $_GET['page'] == $page) {
                                                    $active = 'active';
                                                } elseif (!isset($_GET['page']) && $page == 1) {
                                                    $active = 'active';
                                                } else {
                                                    $active = "";
                                                }
                                                ?>
                                                <li class="<?php echo $active; ?>"><a href="index?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if ($number_of_pages > $nav) {
                                                $next = $nav + 1;
                                                ?>
                                                <li><a href="index?page=<?php echo $next; ?>">Next &raquo;</a></li>
                                                <?php
                                            } else {
                                                ?>
                                                <li class="disabled"><a href="#">Next &raquo;</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                </div>
                                <?php
                            }
                            ?>
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
