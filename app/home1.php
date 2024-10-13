<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
include 'phpInsight-master/autoload.php';

$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

$alert = "";

if ($role != "admin") {
    header("location:login");
}

if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    
    $status = "Received";
    $update_complaint = mysqli_query($conn, "update complaints set status='$status'where post_id ='$id'");
    
    $userMsg = mysqli_fetch_array(mysqli_query($conn, "select * from complaints where post_id ='$id'"));
    $msgId = $userMsg["post_id"];
    $userName = $userMsg["r_id"];
    $message = $userMsg["complaints"];
    $msgStatus = $userMsg["status"];
    $msgDate =$userMsg["date"];
    
    
    
    $userMsg = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email ='$userName'"));
    
    $userPhone = $userMsg["phone"];
    $userHouseNo = $userMsg["houseno"];
    $userArea = $userMsg["area"];
    
    $email_chk = mysqli_query($conn, "select r_id from police_dept where msg_id ='$msgId'");
    $email_avail = mysqli_num_rows($email_chk);

    if ($email_avail > 0) {
        //$alert = 'This message has already been sens to police department!';
        echo "<script>alert('This message has already been send to police department'); window.location.href='home';</script>";
       
    } else {
    
      $insert_pol = mysqli_query($conn, "insert into police_dept values ('$msgId','$userName','$userHouseNo','$userArea','$userPhone','$message','$msgStatus','$msgDate')");

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
                                <h3 class="page-title br-0">Welcome</h3>
                            </div>

                        </div>
                    </div>

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block text-dark font-weight-500">Feedbacks</span>
                                            </div>
                                            <div>
                                                <span class="badge badge-primary badge-sm">+..%</span>
                                            </div>
                                        </div>
                                        <div>
                                            <?php
                                            $num_feed_backs = mysqli_num_rows(mysqli_query($conn, "select * from contact"));
                                            ?>
                                            <span class="d-block text-dark mb-5 font-size-30"><?php echo $num_feed_backs ?></span>
                                            <small class="d-block">Total Users Feedback</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block text-dark font-weight-500">Residence</span>
                                            </div>
                                            <?php
                                            $num_members = mysqli_num_rows(mysqli_query($conn, "select * from residence"));
                                            ?>
                                            <div>
                                                <span class="badge badge-pill badge-sm">+..%</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block text-dark mb-5 font-size-30"><?php echo $num_members; ?></span>
                                            <small class="d-block">Total Users</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block text-dark font-weight-500">Security Complaint</span>
                                            </div>
                                            <div>
                                                <span class="badge badge-light badge-sm">...%</span>
                                            </div>
                                        </div>
                                        <?php
                                        $num_post = mysqli_num_rows(mysqli_query($conn, "select * from complaints"));
                                        ?>
                                        <div>
                                            <span class="d-block text-dark mb-5 font-size-30"><?php echo $num_post; ?></span>
                                            <small class="d-block">Total Post</small>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="blog-details-wrapper">
                        </div><!-- blog-details-wrapper end -->
                    </section>

                    <section class="content">
                        <div class="row col-md-12">
                            <div class="publisher publisher-multi col-md-12 bg-white b-1 mb-30">
                                <p>Residence Timeline Post</p>
                                <!--  <form method="post" action="" enctype="multipart/form-data">
                                      <textarea  name="msg" class="publisher-input col-md-12 auto-expand" rows="4" placeholder="Write something"></textarea>
                                      <div class="flexbox">
                                          <div class="gap-items">
  
                                              </span>
                                              <a class="publisher-btn" href="#"><i class="fa fa-map-marker"></i></a>
                                              <a class="publisher-btn" href="#"><i class="fa fa-smile-o"></i></a>
                                          </div>
  
                                          <button type="submit" name="add" class="btn btn-sm btn-bold btn-primary">Post</button>
                                      </div>
                                  </form> -->

                            </div>
                            <?php
                            //Start Pagination 
                            $item_per_page = 4;
                            $number_of_items = mysqli_num_rows(mysqli_query($conn, "select * from complaints order by date desc"));
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
                                $row1 = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email ='$username'  "));
                                $member_id = $row1["r_id"];
                                $fullname = $row1["fullname"];
                                $email = $row1["email"];
                                $gender = $row1["gender"];
                                $reg_date = date_format(date_create($row1["reg_date"]), "d, M Y H:i");


                                //getting user departmnet name
                              
                                //getting number of posted comment with their various polarity
                                $num_of_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid'"));
                                $num_pos_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='positive'"));
                                $num_neg_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='negative'"));
                                $num_neu_comments = mysqli_num_rows(mysqli_query($conn, "select * from comment where postid='$postid' and sentiment='nuetral'"));
                                ?>
                                <div class="box">
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

                                    <div class="box-body bb-1 border-fade">
                                        <p class="lead"><?php echo $message; ?><img align="right" src="img/<?php echo $img; ?>"></p><span></span>

                                        <div class="gap-items-4 mt-10">
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-thumbs-up mr-1"></i> <?php echo $num_pos_comments; ?>
                                            </a>
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-comment mr-1"></i> <?php echo $num_of_comments; ?>
                                            </a>
                                            <a class="text-fade hover-light" href="#">
                                                <i class="fa fa-thumbs-down mr-3"></i> <?php echo $num_neg_comments; ?> 
                                            </a>
                                            <a   href="home.php?update_id=<?php echo $row['post_id']; ?>" class="text-fade hover-light" >
                                                <i class="fa fa-comment mr-1" ></i><b style="color:blueviolet;"><?php echo $compl_status; ?></b>
                                            </a>
                                        </div>
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
                                           // $comment_level = $row1["level"];
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
                                                            <i class="fa fa-comment mr-1"></i> <b style="color:blueviolet;"></b>
                                                        </a>
                                                    </p>
                                                    <p><?php echo $comment_message ?>.</p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                    </div>

                                    <!--    <form method="post" action="" class="publisher bt-1 border-fade">
                                            <img class="avatar avatar-sm" src="../images/icon2.jpg" alt="...">
                                            <input class="publisher-input" name="comment" type="text" placeholder="Add Your Comment">
                                            <input name="postid" type="hidden" value="<?php echo $postid; ?>">
                                            <button type="submit" class="btn btn-sm btn-bold btn-primary" name="addcomment"> <i class="fa fa-arrow-circle-up"></i></button>

                                        </form> -->

                                </div>
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
                                                    <li><a href="home?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
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
                                                    <li class="<?php echo $active; ?>"><a href="home?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if ($number_of_pages > $nav) {
                                                    $next = $nav + 1;
                                                    ?>
                                                    <li><a href="home?page=<?php echo $next; ?>">Next &raquo;</a></li>
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
                                                    <li><a href="home?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
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
                                                    <li class="<?php echo $active; ?>"><a href="home?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if ($number_of_pages > $nav) {
                                                    $next = $nav + 1;
                                                    ?>
                                                    <li><a href="home?page=<?php echo $next; ?>">Next &raquo;</a></li>
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
