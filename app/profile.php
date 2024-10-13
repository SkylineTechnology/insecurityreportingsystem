<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
include 'phpInsight-master/autoload.php';

$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";
$deptid = isset($_SESSION["dept_id"]) ? $_SESSION["dept_id"] : "";


//here i am getting the user records
$rows = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email='$userid'"));
$user_member_id = $rows["r_id"];
$user_fullname = $rows["fullname"];
$user_email = $rows["email"];
$user_gender = $rows["gender"];
$house_no = $rows["houseno"];
$user_area = $rows["area"];
$user_phone = $rows["phone"];
$user_reg_date = date_format(date_create($rows["reg_date"]), "d, M Y H:i");

//getting dept name
//getting user departmnet name
//



if (!$_SESSION) {
    header("location:login");
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

    if (trim($post_msg) != "") {
        $sentiment = new \PHPInsight\Sentiment();
        $category = $sentiment->categorise($post_msg);

        if ($category == "pos") {
            $cat = "positive";
        } elseif ($category == "neu") {
            $cat = "neutral";
        } elseif ($category == "neg") {
            $cat = "negative";
        }

        $post_id = "POST-" . date("Ymisdh");
        $post_date = date("Y-m-d H:i:s");
        $insert_post = mysqli_query($conn, "insert into timeline values ('$post_id','$userid','$post_msg','Unseen','$post_date')");
        if ($insert_post) {
            echo "<script>alert('Your Complaint have send successfully')</script>";
        } else {
            echo "<script>alert('Oops! looks like we are having a downtime, please try after some minutes')</script>";
        }
    } else {
        echo "<script>alert('Post field can not be empty')</script>";
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
                                <h3 class="page-title">Profile</h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">My Profile</li>
                                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <section class="content">

                        <div class="row">
                            <div class="col-12 col-lg-5 col-xl-4">

                                <div class="box box-inverse bg-img" style="background-image: url(../images/gallery/full/1.jpg);" data-overlay="2">
                                    <div class="flexbox px-20 pt-20">
                                        <label class="toggler toggler-danger text-white">
                                            <input type="checkbox">
                                            <i class="fa fa-heart"></i>
                                        </label>
                                        <div class="dropdown">
                                            <a data-toggle="dropdown" href="#"><i class="ti-more-alt rotate-90 text-white"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
                                                <a class="dropdown-item" href="#"><i class="fa fa-picture-o"></i> Shots</a>
                                                <a class="dropdown-item" href="#"><i class="ti-check"></i> Follow</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#"><i class="fa fa-ban"></i> Block</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-body text-center pb-50">
                                        <a href="#">
                                            <img class="avatar avatar-xxl avatar-bordered" src="../images/icon.png" alt="">
                                        </a>
                                        <h4 class="mt-2 mb-0"><a class="hover-primary text-white" href="#"><?php echo $user_fullname; ?></a></h4>
                                        <span><i class="fa fa-map-marker w-20"></i> </span>
                                    </div>

                                    <ul class="box-body flexbox flex-justified text-center" data-overlay="4">
                                        <?php
                                        $num_of_my_post = mysqli_num_rows(mysqli_query($conn, "select * from complaints where r_id='$userid'"));
                                        //$num_of_my_folowers = mysqli_num_rows(mysqli_query($conn, "select * from residence where dept_id='$deptid'"));
                                        ?>
                                      

                                        <li>
                                            <span class="opacity-60">Security Complaints</span><br>
                                            <span class="font-size-20"><?php echo $num_of_my_post; ?></span>
                                        </li>
                                    </ul>
                                </div>				

                                <!-- Profile Image -->
                                <div class="box">
                                    <div class="box-body box-profile">            
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <p>Email :<span class="text-gray pl-10"><?php echo $user_email; ?></span> </p>
                                                    <p>House Number :<span class="text-gray pl-10"><?php echo $house_no; ?></span></p>
                                                    <p>Area :<span class="text-gray pl-10"><?php echo $user_area; ?></span></p>
                                                    <p>Area :<span class="text-gray pl-10"><?php echo $user_phone; ?></span></p>
                                                </div>
                                            </div>
                                          

                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>


                                <div class="box box-inverse box-carousel slide" data-ride="carousel" style="background-color: #00aced">
                                    <div class="box-header no-border">
                                        <span class="fa fa-twitter font-size-30"></span>
                                        <div class="box-tools pull-right">
                                            <h5 class="box-title box-title-bold">Carousel feed</h5>
                                        </div>
                                    </div>

                                    <div class="carousel-inner">
                                        <blockquote class="blockquote blockquote-inverse no-border m-0 py-15 carousel-item active">
                                            <p>Holisticly benchmark plug imperatives for multifunctional deliverables. Seamlessly incubate cross functional action.</p>
                                            <div class="flexbox">
                                                <time class="text-white" datetime="2017-11-22 20:00">22 November, 2017</time>
                                                <span><i class="fa fa-heart"></i> 62</span>
                                            </div>
                                        </blockquote>

                                        <blockquote class="blockquote blockquote-inverse no-border m-0 py-15 carousel-item">
                                            <p>Uniquely revolutionize leveraged catalysts for change for world-class web services. Efficiently underwhelm competitive.</p>
                                            <div class="flexbox">
                                                <time class="text-white" datetime="2017-11-22 20:00">22 November, 2017</time>
                                                <span><i class="fa fa-heart"></i> 45</span>
                                            </div>
                                        </blockquote>

                                        <blockquote class="blockquote blockquote-inverse no-border m-0 py-15 carousel-item">
                                            <p>Enthusiastically optimize cross-media manufactured products without process-centric web services. Conveniently.</p>
                                            <div class="flexbox">
                                                <time class="text-white" datetime="2017-11-22 20:00">22 November, 2017</time>
                                                <span><i class="fa fa-heart"></i> 65</span>
                                            </div>
                                        </blockquote>
                                    </div>

                                </div>

                                <div class="box box-inverse" style="background-color: #3b5998">
                                    <div class="box-header no-border">
                                        <span class="fa fa-facebook font-size-30"></span>
                                        <div class="box-tools pull-right">
                                            <h5 class="box-title box-title-bold">Facebook feed</h5>
                                        </div>
                                    </div>

                                    <blockquote class="blockquote blockquote-inverse no-border m-0 py-15">
                                        <p>Holisticly benchmark plug imperatives for multifunctional deliverables. Seamlessly incubate cross functional action.</p>
                                        <div class="flexbox">
                                            <time class="text-white" datetime="2017-11-21 20:00">21 November, 2017</time>
                                            <span><i class="fa fa-heart"></i> 75</span>
                                        </div>
                                    </blockquote>
                                </div>

                            </div>
                            <div class="col-12 col-lg-7 col-xl-8">

                                <div class="nav-tabs-custom box-profile">
                                    <ul class="nav nav-tabs">
                                        <li><a class="active" href="#usertimeline" data-toggle="tab">Report</a></li>
                                        <!--    <li><a href="#activity" data-toggle="tab">Activity</a></li>
                                            <li><a href="#settings" data-toggle="tab">Settings</a></li> -->
                                    </ul>

                                    <div class="tab-content">

                                        <div class="active tab-pane" id="usertimeline">

                                            <div class="row col-md-12">
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
                                                $number_of_items = mysqli_num_rows(mysqli_query($conn, "select * from complaints where r_id='$userid' order by date desc"));
                                                $number_of_pages = ceil($number_of_items / $item_per_page);

                                                if (!isset($_GET['page'])) {
                                                    $page = 1;
                                                } else {
                                                    $page = $_GET['page'];
                                                }
                                                $current_page_first_item = ($page - 1) * $item_per_page;
                                                //End Pagination
                                                //online post from users of thesame department can be seen here
                                                $get_post = mysqli_query($conn, "select * from complaints where r_id='$userid' order by date desc limit $current_page_first_item,$item_per_page");
                                                while ($row = mysqli_fetch_array($get_post)) {
                                                    $postid = $row["post_id"];
                                                    $username = $row["r_id"];
                                                    //$dept_id = $row["dept_id"];
                                                    $message = $row["complaints"];
                                                    //$post_sentiment = $row["sentiment"];
                                                    $date = gatDates($row["date"]);

                                                    //getting user records
                                                    $row1 = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email='$username'"));
                                                    $member_id = $row1["r_id"];
                                                    $fullname = $row1["fullname"];
                                                    $email = $row1["email"];
                                                    $gender = $row1["gender"];
                                                    $compl_status = $row["status"];
                                                    $reg_date = date_format(date_create($row1["reg_date"]), "d, M Y H:i");



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
                                                            <p class="lead"><?php echo $message; ?></p>

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
                                                                <a class="text-fade hover-light" href="#">
                                                                    <i class="fa fa-comment mr-1"></i><b style="color:blueviolet;"><?php echo $compl_status; ?></b>
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
                                                                                <i class="fa fa-comment mr-1"></i>
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

                                                        </form>

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
                                                                        <li><a href="profile?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
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
                                                                        <li class="<?php echo $active; ?>"><a href="profile?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if ($number_of_pages > $nav) {
                                                                        $next = $nav + 1;
                                                                        ?>
                                                                        <li><a href="profile?page=<?php echo $next; ?>">Next &raquo;</a></li>
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
                                                                        <li><a href="profile?page=<?php echo $prev; ?>">&laquo; Previous</a></li>
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
                                                                        <li class="<?php echo $active; ?>"><a href="profile?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if ($number_of_pages > $nav) {
                                                                        $next = $nav + 1;
                                                                        ?>
                                                                        <li><a href="profile?page=<?php echo $next; ?>">Next &raquo;</a></li>
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

                                        </div>    
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="activity">			

                                            <div class="box p-15">				
                                                <!-- Post -->
                                                <div class="post">
                                                    <div class="user-block">
                                                        <img class="img-bordered-sm rounded-circle" src="../images/user1-128x128.jpg" alt="user image">
                                                        <span class="username">
                                                            <a href="#">Brayden</a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <span class="description">5 minutes ago</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="activitytimeline">
                                                        <p>
                                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
                                                        </p>
                                                        <ul class="list-inline">
                                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                                            </li>
                                                            <li class="pull-right">
                                                                <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                                                    (5)</a></li>
                                                        </ul>
                                                        <form class="form-element">
                                                            <input class="form-control input-sm" type="text" placeholder="Type a comment">
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.post -->

                                                <!-- Post -->
                                                <div class="post">
                                                    <div class="user-block">
                                                        <img class="img-bordered-sm rounded-circle" src="../images/user6-128x128.jpg" alt="user image">
                                                        <span class="username">
                                                            <a href="#">Evan</a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <span class="description">5 minutes ago</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="activitytimeline">
                                                        <div class="row mb-20">
                                                            <div class="col-sm-6">
                                                                <img class="img-fluid" src="../images/photo1.png" alt="Photo">
                                                            </div>
                                                            <!-- /.col -->
                                                            <div class="col-sm-6">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <img class="img-fluid" src="../images/photo2.png" alt="Photo">
                                                                        <br><br>
                                                                        <img class="img-fluid" src="../images/photo3.jpg" alt="Photo">
                                                                    </div>
                                                                    <!-- /.col -->
                                                                    <div class="col-sm-6">
                                                                        <img class="img-fluid" src="../images/photo4.jpg" alt="Photo">
                                                                        <br><br>
                                                                        <img class="img-fluid" src="../images/photo1.png" alt="Photo">
                                                                    </div>
                                                                    <!-- /.col -->
                                                                </div>
                                                                <!-- /.row -->
                                                            </div>
                                                            <!-- /.col -->
                                                        </div>
                                                        <!-- /.row -->

                                                        <ul class="list-inline">
                                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                                            </li>
                                                            <li class="pull-right">
                                                                <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                                                    (5)</a></li>
                                                        </ul>

                                                        <form class="form-element">
                                                            <input class="form-control input-sm" type="text" placeholder="Type a comment">
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.post -->

                                                <!-- Post -->
                                                <div class="post clearfix">
                                                    <div class="user-block">
                                                        <img class="img-bordered-sm rounded-circle" src="../images/user7-128x128.jpg" alt="user image">
                                                        <span class="username">
                                                            <a href="#">Nicholas</a>
                                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        <span class="description">5 minutes ago</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="activitytimeline">
                                                        <p>
                                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
                                                        </p>

                                                        <form class="form-horizontal form-element">
                                                            <div class="form-group row no-gutters">
                                                                <div class="col-sm-9">
                                                                    <input class="form-control input-sm" placeholder="Response">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <button type="submit" class="btn btn-rounded btn-danger pull-right btn-block btn-sm">Send</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.post -->
                                            </div>

                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="settings">		

                                            <div class="box p-15">		
                                                <form class="form-horizontal form-element col-12">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control" id="inputName" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control" id="inputEmail" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputPhone" class="col-sm-2 control-label">Phone</label>

                                                        <div class="col-sm-10">
                                                            <input type="tel" class="form-control" id="inputPhone" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" id="inputExperience" placeholder=""></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="inputSkills" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="ml-auto col-sm-10">
                                                            <div class="checkbox">
                                                                <input type="checkbox" id="basic_checkbox_1" checked="">
                                                                <label for="basic_checkbox_1"> I agree to the</label>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Terms and Conditions</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="ml-auto col-sm-10">
                                                            <button type="submit" class="btn btn-rounded btn-success">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>			  
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>			  
                        </div>
                        <!-- /.row -->

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
