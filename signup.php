<?php
// Include necessary files
include "database/db.php";
include "email.php";
require 'googleApi/vendor/autoload.php';

$google_client_id = '721204767071-b10cq6dsm31pm6ocea0q1c93t6gia61l.apps.googleusercontent.com'; // Your Google Client ID
$google_client_secret = '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'; // Your Google Client Secret
$google_redirect_uri = 'https://localhost/ems/customer/home.php';
// Google Client configuration


$google_client = new Google_Client();
$google_client->setClientId($google_client_id);
$google_client->setClientSecret($google_client_secret);
$google_client->setRedirectUri($google_redirect_uri);
$google_client->addScope('email');
$google_client->addScope('profile');
$googleAuthUrl = $google_client->createAuthUrl();
if (isset($_GET['code'])) {
  $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
  if (!isset($token['error'])) {
      $password = "Qch@123"; // Default password for Google Sign-In users
      $google_client->setAccessToken($token['access_token']);
      $_SESSION['access_token'] = $token['access_token'];
      
      $google_service = new Google_Service_Oauth2($google_client);
      $data = $google_service->userinfo->get();
      
      if (!empty($data['email']) && !empty($data['given_name']) && !empty($data['family_name'])) {
        
          $name = $data['given_name'] . " " . $data['family_name'];
          $email = $data['email'];
          echo $email;
          echo $name;
          $contact = ''; // Initialize contact number
          
          // Check if the user exists in the database
          $query = "SELECT * FROM tbl_user WHERE email = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $email);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows === 0) {
              // User doesn't exist, insert into database
              $insertQuery = "INSERT INTO tbl_user (name, email, password, contact, user_type, status, date) VALUES (?, ?, ?, ?, ?, ?, ?)";
              $insertStmt = $conn->prepare($insertQuery);
              
              // Assuming other fields have default values or are not required during Google Sign-In
              $userType = 'C'; // Assuming a default user type
              $status = 1; // Assuming a default status
              $date = date("Y-m-d"); // Assuming you want to store the current date
              
              // Bind parameters and execute the query
              $insertStmt->bind_param("sssssis", $name, $email, $password, $contact, $userType, $status, $date);
              $insertStmt->execute();
          }

      }
  } else {
      echo $token['error'];
  }
}

$customerNameErr = $customerEmailErr = $customerContactErr = $customerPasswordErr = "";
$vendorNameErr = $vendorEmailErr = $vendorContactErr = $vendorTitleErr = $vendorCategoryErr = $vendorPriceErr = $vendorDetailsErr = $vendorPasswordErr = "";

if (isset($_POST["btn_customer"])) {

  $recaptcha_response = $_POST['g-recaptcha-response'];
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $secret = '6LdbHk0pAAAAAIwvUGGYRkjF42htdx6aVI2RGOZn'; // Replace with your reCAPTCHA secret key

  $options = [
      'http' => [
          'method' => 'POST',
          'header' => 'Content-Type: application/x-www-form-urlencoded',
          'content' => http_build_query([
              'secret' => $secret,
              'response' => $recaptcha_response,
          ]),
      ],
  ];

  $context = stream_context_create($options);
  $response = file_get_contents($url, false, $context);
  $result = json_decode($response);

  if (!$result->success) {
      $vendorErrors[] = "reCAPTCHA verification failed. Please try again.";
  }
  

  $customerName = $_POST["name"];
  $customerEmail = $_POST["email"];
  $customerPassword = $_POST["password"];
  $customerContact = $_POST["contact"];
  $customerErrors = array();

  if (!preg_match("/^[A-Za-z]+$/", $customerName)) {
    $customerNameErr = "Name must contain only alphabets";
    $customerErrors[] = $customerNameErr;
  }

  if (!preg_match("/^[6-9]\d{9}$/", $customerContact) || strlen($customerContact) !== 10) {
    $customerContactErr = "Invalid phone number";
    $customerErrors[] = $customerContactErr;
  }

  if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    $customerEmailErr = "Invalid email address";
    $customerErrors[] = $customerEmailErr;
  }

  $emailCheck = mysqli_query($conn, "SELECT * FROM tbl_user WHERE email='$customerEmail'");
  if (mysqli_num_rows($emailCheck) > 0) {
    $customerEmailErr = "Email already exists";
    $customerErrors[] = $customerEmailErr;
  }

  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{6,}$/", $customerPassword)) {
    $customerPasswordErr = "Password must contain at least 6 characters, one uppercase, one number, and one special character";
    $customerErrors[] = $customerPasswordErr;
  }

  if (empty($customerErrors)) {
    // Generate OTP
    $otp = rand(1000, 9999);
    
    // Store registration type and OTP in session
    session_start();
    $_SESSION['registration_type'] = 'customer';
    $_SESSION['otp'] = $otp;
    // $emailSent = sendOtpEmail($customerEmail, $otp);
    // send mail
    $vc = new VerificationCode($customerEmail,$otp);
    $vc->sendMail();

    $_SESSION["user_data"] = $_POST; 

    if (true) {
        // Redirect to OTP verification page
        header("Location: otp_verification.php");
    } else {
        echo  "<script>alert('Email sending failed. Please try again.');</script>";
    }
  }
}

if (isset($_POST["btn_vendor"])) {

    $recaptcha_response = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $secret = '6LdbHk0pAAAAAIwvUGGYRkjF42htdx6aVI2RGOZn'; // Replace with your reCAPTCHA secret key

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'secret' => $secret,
                'response' => $recaptcha_response,
            ]),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response);

    if (!$result->success) {
        $vendorErrors[] = "reCAPTCHA verification failed. Please try again.";
    }
  $vendorName = $_POST["name"];
  $vendorEmail = $_POST["email"];
  $vendorPassword = $_POST["password"];
  $vendorContact = $_POST["contact"];
 
  $vendorErrors = array();

  if (!preg_match("/^[A-Za-z]+$/", $vendorName)) {
    $vendorNameErr = "Name must contain only alphabets";
    $vendorErrors[] = $vendorNameErr;
  }

  if (!preg_match("/^[6-9]\d{9}$/", $vendorContact) || strlen($vendorContact) !== 10) {
    $vendorContactErr = "Invalid phone number";
    $vendorErrors[] = $vendorContactErr;
  }

  if (!filter_var($vendorEmail, FILTER_VALIDATE_EMAIL)) {
    $vendorEmailErr = "Invalid email address";
    $vendorErrors[] = $vendorEmailErr;
  }

  $emailCheck = mysqli_query($conn, "SELECT * FROM tbl_user WHERE email='$vendorEmail'");
  if (mysqli_num_rows($emailCheck) > 0) {
    $vendorEmailErr = "Email already exists";
    $vendorErrors[] = $vendorEmailErr;
  }

  $mobileCheck = mysqli_query($conn, "SELECT * FROM tbl_user WHERE contact='$vendorContact'");
  if (mysqli_num_rows($mobileCheck) > 0) {
    $vendorContactErr = "Mobile number already exists";
    $vendorErrors[] = $vendorContactErr;
  }

  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{6,}$/", $vendorPassword)) {
    $vendorPasswordErr = "Password must contain at least 6 characters, one uppercase, one number, and one special character";
    $vendorErrors[] = $vendorPasswordErr;
  }


  if (empty($vendorErrors)) {
    $otp = rand(1000, 9999);
    
    // Store registration type and OTP in session
    session_start();
    $_SESSION['registration_type'] = 'vendor';
    $_SESSION['otp'] = $otp;
  
    $vc = new VerificationCode($vendorEmail,$otp);
    $vc->sendMail();

    $_SESSION["user_data"] = $_POST; 
   
     if (true) {
        // Redirect to OTP verification page
      header("Location: otp_verification.php");
      } else {
        echo  "<script>alert('Email sending failed. Please try again.');</script>";
    }
  }
}

  
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Registeration</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

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

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  




  </head>

  <body>
    <!-- Content -->
    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Register Card -->
          <div class="card p-2">
            
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="/ems/" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <span style="color: var(--bs-primary)">
                    
                  </span>
                </span>
                <span class="app-brand-text demo text-heading fw-semibold">Be'eventful Registration</span>
              </a>
            </div>
            <!-- /Logo -->
            <div class="card-body mt-2">  
              <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link active"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-pills-justified-home"
                      aria-controls="navs-pills-justified-home"
                      aria-selected="true">
                      <i class="tf-icons mdi mdi-home-outline me-1"></i><span class="d-none d-sm-block">Customer</span>
                    </button>
                  </li>
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-pills-justified-profile"
                      aria-controls="navs-pills-justified-profile"
                      aria-selected="false">
                      <i class="tf-icons mdi mdi-account-outline me-1"></i
                      ><span class="d-none d-sm-block">Vendor</span>
                    </button>
                  </li>
                  
                </ul>
                <div class="tab-content">
                  <!--PANEL 1-->
                  <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                  <form id="formAuthentication" class="mb-3" action="" method="POST">
                    <div class="form-floating form-floating-outline mb-3">
                      <input type="text" class="form-control" id="username" name="name" placeholder="Enter your name" autofocus />
                      <label for="username">Name</label>
                      <span class="text-danger"><?php echo $customerNameErr; ?></span>
                    </div>
                    <div class="form-floating form-floating-outline mb-3">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" />
                      <label for="email">Email</label>
                      <span class="text-danger"><?php echo $customerEmailErr; ?></span>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                      <input class="form-control" type="tel" name="contact" placeholder="" id="html5-tel-input">
                      <label for="html5-tel-input">Phone</label>
                      <span class="text-danger"><?php echo $customerContactErr; ?></span>
                    </div>
                    <div class="mb-3 form-password-toggle">
                      <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                          <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                          <label for="password">Password</label>
                          <span class="text-danger"><?php echo $customerPasswordErr; ?></span>
                        </div>
                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                      </div>
                    </div>
                    <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="6LdbHk0pAAAAANVrFMEapfYFeatw-68L81ARDkU7"></div> </div>
                    <div class="mb-3">
            <a href="<?php echo $googleAuthUrl; ?>" class="btn btn-danger d-grid w-100">
                <i class="mdi mdi-google"></i> Sign in with Google
            </a>
        </div>
                    <button class="btn btn-primary d-grid w-100" type="submit" name="btn_customer">Sign up</button>
                  </form>
                </div>
                <!--vendor!-->
                  <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                  <form id="formAuthentication" class="mb-3" action="" method="POST" enctype="multipart/form-data" >
                    <div class="form-floating form-floating-outline mb-3">
                      <input type="text" class="form-control" id="username" name="name" placeholder="Enter your name" autofocus />
                      <label for="username">Name</label>
                      <span class="text-danger"><?php echo $vendorNameErr; ?></span>
                    </div>
                    <div class="form-floating form-floating-outline mb-3">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" />
                      <label for "email">Email</label>
                      <span class="text-danger"><?php echo $vendorEmailErr; ?></span>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                      <input class="form-control" type="tel" name="contact" placeholder="" id="html5-tel-input">
                      <label for="html5-tel-input">Phone</label>
                      <span class="text-danger"><?php echo $vendorContactErr; ?></span>
                    </div>
                    <div class="mb-3 form-password-toggle">
                      <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                          <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                          <label for="password">Password</label>
                          <span class="text-danger"><?php echo $vendorPasswordErr; ?></span>
                        </div>
                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                      </div>
                    </div>
                    <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="6LdbHk0pAAAAANVrFMEapfYFeatw-68L81ARDkU7"></div></div>
                    <div class="mb-3">
            <a href="<?php echo $googleAuthUrl; ?>" class="btn btn-danger d-grid w-100">
                <i class="mdi mdi-google"></i> Sign in with Google
            </a>
        </div>
                    <button class="btn btn-primary d-grid w-100" type="submit" name="btn_vendor">Sign up</button>
                  </form>
                </div>
              </div>
            </div>
            <p class="text-center">
              <span>Already have an account?</span>
              <a href="login.php">
                <span>Sign in instead</span>
              </a>
            </p>
          </div>
        </div>
          <!-- Register Card -->
          <img
            src="assets/img/illustrations/tree-3.png"
            alt="auth-tree"
            class="authentication-image-object-left d-none d-lg-block" />
          <img
            src="assets/img/illustrations/auth-basic-mask-light.png"
            class="authentication-image d-none d-lg-block"
            alt="triangle-bg"
            data-app-light-img="illustrations/auth-basic-mask-light.png"
            data-app-dark-img="illustrations/auth-basic-mask-dark.png" />
          <img
            src="assets/img/illustrations/tree.png"
            alt="auth-tree"
            class="authentication-image-object-right d-none d-lg-block" />
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
   <!-- Add this inside the <head> tag, replace YOUR_CLIENT_ID with your actual Client ID -->

  </body>
</html>