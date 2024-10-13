<?php
session_start();
include 'app/includes/connection.php';
include 'app/includes/functions.php';

$error = "";
if (isset($_POST["login"])) {
    $email = secure($conn, $_POST["username"]);
    $password = md5(secure($conn, $_POST["password"]));

    $get_rc = mysqli_query($conn, "select * from login where username='$email' and password='$password'");
    $num_rows = mysqli_num_rows($get_rc);
    if ($num_rows > 0) {
        $row = mysqli_fetch_array($get_rc);
        $_SESSION["userid"] = $row["username"];
        $_SESSION["urole"] = $row["role"];
        if ($row["role"] == "user") {
            $get_dept_id = mysqli_fetch_array(mysqli_query($conn, "select * from residence where email='$email'")) or die(mysqli_error($conn));
            $_SESSION["userid"] = $row["username"];
            $_SESSION["urole"] = $row["role"];
            $_SESSION["dept_id"] = $get_dept_id["dept_id"];
            $_SESSION["status"] = $row["status"];
            header("location:app/index");
        } elseif ($row["role"] == "police") {
            $_SESSION["userid"] = $row["username"];
            $_SESSION["urole"] = $row["role"];
            $_SESSION["status"] = $row["status"];
            header("location:app/index");
        }elseif ($row["role"] == "admin") {
            $_SESSION["userid"] = $row["username"];
            $_SESSION["urole"] = $row["role"];
            $_SESSION["status"] = $row["status"];
            header("location:app/home");
        }
    } else {
        echo " <div class='alert alert-info alert-dismissible'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            <h4><i class='icon fa fa-warning'></i> Alert!</h4>
                                           <p style=color:'red';> Incorrect username or password, please check and try again.</p>
                                        </div>";
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
        <link rel="stylesheet" href="app/css/vendors_css.css">

        <!-- Style-->  
        <link rel="stylesheet" href="app/css/style.css">
        <link rel="stylesheet" href="app/css/skin_color.css">	

    </head>
    <body style=" background-image: url(images/bg.jpg)" class="hold-transition bg-gradient-primary">

        <div class="container h-p100">
            <div class="row align-items-center justify-content-md-center h-p100">	

                <div class="col-12">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="content-top-agile p-10">
                                <h2 class="text-white"><?php echo $sitename; ?></h2>
                                <p class="text-white-50">Sign in to start your session</p>
                                <p class="text-warning"><?php echo $error; ?></p>
                            </div>
                            <div class="p-30 rounded30 box-shadowed b-2 b-dashed">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent text-white"><i class="ti-user"></i></span>
                                            </div>
                                            <input type="text" name="username" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text  bg-transparent text-white"><i class="ti-lock"></i></span>
                                            </div>
                                            <input type="password" name="password" required="" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="checkbox text-white">
                                                <input type="checkbox" id="basic_checkbox_1" >
                                                <label for="basic_checkbox_1">Remember Me</label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6">
                                            <div class="fog-pwd text-right">
                                                <a href="app/registration" class="text-white hover-info"><i class="ion ion-record"></i> Not Registered?</a><br>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12 text-center">
                                            <button type="submit" name="login" class="btn btn-info btn-rounded mt-10">SIGN IN</button>
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
