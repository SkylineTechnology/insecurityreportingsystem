<?php
session_start();
include 'includes/connection.php';
include 'includes/functions.php';

$alert = "";
if (isset($_POST["reg"])) {
    $fullname = secure($conn, $_POST["fullname"]);
    $email = $_POST["email"];
    $password = md5(secure($conn, $_POST["password"]));
    $confirmpass = md5(secure($conn, $_POST["confirmpass"]));
    $gender = secure($conn, $_POST["gender"]);
    $phone = secure($conn, $_POST["phone"]);
    $area = secure($conn, $_POST["area"]);
    $hnumber = secure($conn, $_POST["hnumber"]);
    $user_code = uniqueCode($conn);

    $reg_date = date("Y-m-d H:i:s");

    $email_chk = mysqli_query($conn, "select email from residence where email='$email'");
    $email_avail = mysqli_num_rows($email_chk);

    if ($email_avail > 0) {
        $alert = 'This email has already been registered, Please use another email!';
    } else {
        if($password == $confirmpass){

        $reg_member = mysqli_query($conn, "insert into residence values ('$user_code','$fullname','$gender','$email','$phone','$hnumber','$area','$reg_date')");
        if ($reg_member) {
            $insert_login = mysqli_query($conn, "insert into login values ('$email','$password','user','no')");
            if ($insert_login) {
               echo "<script>alert('Registration Successfull'); window.location.href='../index';</script>";
              //  header('location:login');
            } else {
                $alert = 'Oops looks like we are having a downtime, please try after some munites';
            }
        } else {
            $alert = 'Registration Failed, Please Try after some minutes';
        }
    }else{
        echo "<script>alert('Password does not Tally with Confirm Password. Please check and try again...'); </script>";
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="favicon.png" sizes="16x16">


        <title><?php echo $sitename; ?> - Log in </title>

        <!-- Vendors Style-->
        <link rel="stylesheet" href="css/vendors_css.css">

        <!-- Style-->  
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/skin_color.css">	

    </head>
    <body style=" background-image: url(../images/bg.jpg); background-size: cover;" class="hold-transition bg-gradient-primary">

        <div class="container h-p100">
            <div class="row align-items-center justify-content-md-center h-p100">	

                <div class="col-12">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-lg-5 col-md-6 col-12">
                            <div class="content-top-agile p-10">
                                <h2 class="text-white"><?php echo $sitename; ?></h2>
                                <p class="text-white-50">Sign Up</p>
                                <p class="text-warning"><?php echo $alert; ?></p>
                            </div>
                            <div class="p-30 rounded30 box-shadowed b-2 b-dashed">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-user"></i></span>
                                            </div>
                                            <input type="text" name="fullname" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Fullname">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-user"></i></span>
                                            </div>
                                            <select class="form-control pl-15 bg-transparent text-info" name="gender" required="">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-email"></i></span>
                                            </div>
                                            <input type="email" name="email" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-email"></i></span>
                                            </div>
                                            <input type="text" name="phone" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Phone Number">
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent text-white"><i class="ti-lock"></i></span>
                                            </div>
                                            <input type="number" name="hnumber" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="House address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-archive"></i></span>
                                            </div>
                                            <select class="form-control pl-15 bg-transparent text-info" name="area" required="">
                                                <option value="">Select Area</option>
                                                <option value="yogbo road">Yogbo Road</option>
                                                <option value="mission ward">Mission ward</option>
                                                <option value="udeh street">Udeh street</option>
                                                <option value="vetinary layout">Vetinary Layout</option>
                                                <option value="abatour">Abatour</option>
                                                <option value="nasme junction">NASME Junction</option>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent text-white"><i class="ti-lock"></i></span>
                                            </div>
                                            <input type="password" name="password" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Password">
                                            
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent text-white"><i class="ti-lock"></i></span>
                                            </div>
                                            
                                            <input type="password" name="confirmpass" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Confirm Password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="fog-pwd text-left">
                                                <a href="../index" class="text-white hover-info"><i class="ion ion-record"></i> Already Registered?</a><br>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" name="reg" class="btn btn-info btn-rounded mt-10">SIGN UP</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>														


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Vendor JS -->
        <script src="js/vendors.min.js"></script>

    </body>

    <!-- Mirrored from html.psdtohtmlexpert.com/admin/famosa-admin/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 23 Dec 2020 08:43:51 GMT -->
</html>
