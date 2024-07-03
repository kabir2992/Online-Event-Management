<?php
//error_reporting(E_ALL);
session_start();
include "database/db.php";
if (isset($_POST["submit"])){
if (true) {
    // Check if the OTP session variable exists
    if (isset($_SESSION['otp'])) {
        $enteredOTP = $_POST["otp"]; // Get the entered OTP
        $sentOTP = $_SESSION['otp']; // Get the stored OTP

        if ($enteredOTP == $sentOTP) {
            unset($_SESSION['otp']);
            // OTP is correct, user is verified
            // Now proceed with data insertion based on the type (customer or vendor)

            // echo "<pre>";
            // print_r($_SESSION["user_data"]);
            // echo "</pre>";

            $user_type = trim($_SESSION['registration_type']);

            if ($user_type == "customer") {
                // Customer Registration
                $name = $_SESSION["user_data"]["name"];
                $email = $_SESSION["user_data"]["email"];
                $password = password_hash($_SESSION["user_data"]["password"], PASSWORD_DEFAULT);
                $contact = $_SESSION["user_data"]["contact"];

                // $qry = "INSERT INTO tbl_user VALUES (DEFAULT, '$name', '$email', '$password', '$contact', 'C', 1)";

                // if (mysqli_query($conn, $qry)) {
                //     // Redirect to the login page after successful registration
                //     header("Location: /ems/login.php");
                // } else {
                //     echo "<script>alert('Something went wrong! Please try again...');</script>";
                // }

                $uType = "C";
                $sql = "INSERT INTO tbl_user(name,email,password,contact,user_type) VALUES(?,?,?,?,?);";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sssss",$name,$email,$password,$contact,$uType);
                    $stmt->execute();
                }
                $stmt->close();
                unset($_SESSION['user_type']);

                header("Location: /ems/login.php");


            } elseif ($user_type == "vendor") {
                // Vendor Registration

                //--------------------------------
                $name = $_SESSION["user_data"]["name"];
                $email = $_SESSION["user_data"]["email"];
                $password = password_hash($_SESSION["user_data"]["password"], PASSWORD_DEFAULT);
                $contact = $_SESSION["user_data"]["contact"];
                $last_insert_id = 0;
                $uType = "V";
                $uStatus = 0;
                $sql = "INSERT INTO tbl_user(name,email,password,contact,user_type,status) VALUES(?,?,?,?,?,?);";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sssssi",$name,$email,$password,$contact,$uType,$uStatus);
                    $stmt->execute();
                }
               
                $stmt->close();
                

                unset($_SESSION['user_type']);

                header("Location: /ems/login.php");

                // $qry = "INSERT INTO tbl_user VALUES (DEFAULT, '$name', '$email', '$password', '$contact', 'V', 0)";

                // if (mysqli_query($conn, $qry)) {
                //     $last_id = mysqli_insert_id($conn);

                //     $qry1 = "INSERT INTO tbl_vendor_data VALUES (DEFAULT, '$title', '$category', '$price', '$details', $last_id, 0)";

                //     if (mysqli_query($conn, $qry1)) {
                //         // Redirect to the login page after successful registration
                //         header("Location: /ems/login.php");
                //     } else {
                //         echo "<script>alert('Something went wrong! Please try again...');</script>";
                //     }
                // } else {
                //     echo "<script>alert('Something went wrong! Please try again...');</script>";
                // }
            }
        } else {
            // Incorrect OTP
            echo "<script>alert('Incorrect OTP. Please try again.');</script>";
        }

        // Clear the OTP from the session
        
    } else {
        // OTP session variable not set
        echo "<script>alert('OTP session variable not found. Please request a new OTP.');</script>";
    }
}
}
?>



<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>OTP Verification</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <!-- Include the Google Font for Roboto -->
    <link href="https://fonts.googleapis.com/css?family=pacifico:300,400,500,600,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Custom CSS to apply the Google Font -->
    <style>
        body {
            font-family: 'Pacifico', cursive;
        }
    </style>
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- OTP Verification -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="/ems/" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-heading fw-semibold">Be'eventful</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-2">
                        <!-- OTP Form -->
                        <form id="otpVerificationForm" class="mb-3" action="" method="POST">
                            <p class="text-center">Please enter the OTP sent to your email.</p>
                            <div class="form-floating form-floating-outline mb-3">
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" autofocus />
                                <label for="otp">OTP</label>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit" name="submit">Verify OTP</button>
                            </div>
                        </form>
                        <!-- /OTP Form -->

                        
                    </div>
                </div>
                <!-- /OTP Verification -->
                <img src="assets/img/illustrations/tree-3.png" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
                <img src="assets/img/illustrations/auth-basic-mask-light.png" class="authentication-image d-none d-lg-block" alt="triangle-bg" data-app-light-img="illustrations/auth-basic-mask-light.png" data-app-dark-img="illustrations/auth-basic-mask-dark.png" />
                <img src="assets/img/illustrations/tree.png" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
