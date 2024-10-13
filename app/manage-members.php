<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';
$role = isset($_SESSION["urole"]) ? $_SESSION["urole"] : "";
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

if ($role != "admin") {
    header("location:login");
}

$alert = "";
if (isset($_POST["add"])) {
    $dname = htmlentities(mysqli_escape_string($conn, $_POST["deptname"]));
    $did = "DEPT-" . date("YmdHis");


    $insert_art = mysqli_query($conn, "insert into lecturers values ('$did','$dname')") or die(mysqli_error($conn));
    if ($insert_art) {
        $alert = 'Department Added Successfully!';
    } else {
        $alert = 'Operations Failed, Please Try after some minutes!';
    }
}

//Deleting member
if (isset($_GET['d'])) {
    $dids = base64_decode($_GET['d']);

    $hide = mysqli_query($conn, "delete from residence where memberid='$dids'");
    if ($hide) {
        echo "<script>alert('Residence deleted successfully!'); window.location.href='manage-members'</script>";
    } else {
        echo "<script>alert('Operations Failed, Please try again after some minutes!')</script>";
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


        <title>Add Departments | <?php echo $sitename; ?> </title>

        <!-- Vendors Style-->
        <link rel="stylesheet" href="css/vendors_css.css">

        <!-- CK-EDITOR -->
        <script src="ckeditor/ckeditor.js"></script>
        <script src="ckeditor/samples/js/sample.js"></script>

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

                    <section class="content mb-0">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="box">
                                    
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block text-dark font-weight-500">Reporter</span>
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
                                                <span class="d-block text-dark font-weight-500">Post</span>
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
                    <!-- Main content -->


                    <div class="row">                       

                        <div class="col-lg-12 col-md-12 col-12">

                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Registered Reporters</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="example6" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>                                                    
                                                    <th>FULLNAME NAME</th>                                              
                                                    
                                                    <th>EMAIL</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                //getting user records
                                                $results = mysqli_query($conn, "select * from residence");
                                                $a = 1;
                                                while ($row = mysqli_fetch_array($results)) {
                                                    $member_id = $row["r_id"];
                                                    $fullname = $row["fullname"];
                                                    $gender = $row["gender"];
                                                    $email = $row["email"];
                                                    $phone = $row["phone"];
                                                    $house_no = $row["houseno"];
                                                    $area = $row["area"];
                                                    $reg_date = date_format(date_create($row["reg_date"]), "d, M Y H:iA");

                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $a; ?></td>
                                                        <td><?php echo $fullname; ?></td> 
                                                        
                                                        <td><?php echo $email; ?></td> 
                                                        <td>
                                                            <a href="user-profile?profile=<?php echo base64_encode($email); ?>" title="View Profile">  <i class="ti-user">View Profile</i></a> 
                                                            
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $a++;
                                                }
                                                ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

        <script src="../assets/vendor_components/datatable/datatables.min.js"></script>
        <script src="js/pages/data-table.js"></script>





        <script src="../assets/vendor_components/apexcharts-bundle/irregular-data-series.js"></script>
        <script src="../assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
        <script src="../assets/vendor_components/echarts/dist/echarts-en.min.js"></script>

        <!-- Famosa Admin App -->
        <script src="js/template.js"></script>
        <script src="js/pages/dashboard.js"></script>


    </body>

    <!-- Mirrored from html.psdtohtmlexpert.com/admin/famosa-admin/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 23 Dec 2020 08:41:47 GMT -->
</html>
