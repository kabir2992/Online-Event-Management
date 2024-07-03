<!DOCTYPE html>
<html>
<head>
  <title>Be'eventful Wedding Planner</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/manager/css/style1.css">
</head>
<body>

<?php 
include "assets/partials/navbar.php";
include "database/db.php";


// $query = "SELECT COUNT(*) AS totalVenues FROM tblvenue"; // Modify the table name if needed
// $result = mysqli_query($conn, $query);

// if ($row = mysqli_fetch_assoc($result)) {
//     $totalVenues = $row['totalVenues'];
// } else {
//     $totalVenues = 0; // Default value if no venues found
// }

// mysqli_close($conn);
?>



<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <h1 class="carousel-main-text">Make Your Event Meaningful with US</h1>
    <!-- Carousel Item 1 -->
    <div class="carousel-item position-relative">
      <img src="images/a4.jpg" class="d-block w-100" height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Business Event</h1>
      </div>
    </div>
    <!-- Carousel Item 2 -->
    <div class="carousel-item position-relative">
      <img src="images/venue.jpg" class="d-block w-100" height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Catering Service</h1>
      </div>
    </div>
    <!-- Carousel Item 3 -->
    <div class="carousel-item position-relative">
      <img src="images/v8.jpg" class="d-block w-100" alt="..." height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Wedding Event</h1>
      </div>
    </div>
    <!-- Carousel Item 4 -->
    <div class="carousel-item active position-relative">
    <img src="images/decoration.jpg" class="d-block w-100" height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Wedding Event</h1>
      </div>
    </div>
    <!-- Carousel Item 5 -->
    <div class="carousel-item position-relative">
      <img src="images/a3.jpg" class="d-block w-100" height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Birthday Event</h1>
      </div>
    </div>
    <!-- Carousel Item 6 -->
    <div class="carousel-item position-relative">
      <img src="images/a5.jpg" class="d-block w-100" height="450">
      <div class="carousel-caption d-none d-md-block mb-5">
        <h1>Wedding Event</h1>
      </div>
    </div>
  </div>
</div>
<div class="container mt-5">
  <h3 class="text-center mb-5">Vendor Categories</h3>
  <div id="carouselVendors" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active">
        <div class="row justify-content-center">
          <div class="col-md-2">
            <!-- Vendor1 -->
            <div class="rounded-circle border border-light mx-auto d-flex justify-content-center align-items-center" style="width: 150px; height: 150px; overflow: hidden;">
              <img src="images/caterer.jpg" alt="Vendor1" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <p class="text-center mt-2">Caterers (39)</p>
          </div>
          <div class="col-md-2">
            <!-- Vendor7 -->
            <div class="rounded-circle border border-light mx-auto d-flex justify-content-center align-items-center" style="width: 150px; height: 150px; overflow: hidden;">
              <img src="images/decoration.jpg" alt="Vendor7" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <p class="text-center mt-2">Decorators (146)</p>
          </div>
          <div class="col-md-2">
            <!-- Vendor6 -->
            <div class="col-md-2">
    <!-- Vendor6 -->
    <div class="rounded-circle border border-light mx-auto d-flex justify-content-center align-items-center" style="width: 150px; height: 150px; overflow: hidden;">
        <img src="images/venue.jpg" alt="Vendor6" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        </div>
        <p class="text-center mt-2">Venues(189)</p>
      </div>

        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- -------------------------------------------------------- -->
<h3 class="text-center mt-5">Gallery</h3>
<div class="row container-xxl mx-auto container-offer-product">
    <div class="col-12 col-md-9 m-0 p-0">
      <div class="row m-0 p-0">
        <div class="col-7 col-sm-7 col-md-6 card-1 m-0 p-0 d-flex align-items-center img-1">
          <p class="card-text">Best Dinning hall with lightning place</p>
        </div>
        <div class="col-5 col-sm-5 col-md-6 card-2 m-0 p-0 d-flex align-items-center img-2">
          <p class="card-text card-txt-white">Memorable Moment</p>
        </div>
      </div>
      <div class="row m-0 p-0">
        <div class="col-5 col-sm-4 card-3 d-flex align-items-center img-3">
          <p class="card-text card-txt-white">Colorful Birthday Party</p>
        </div>
        <div class="col-7 col-sm-4 card-4 d-flex align-items-center img-4">
          <p class="card-text">Amazing open area with gardning</p>
        </div>
        <div class="col-12 col-sm-4 card-5 d-flex align-items-center img-5">
          <p class="card-text">Latest Corporate Meeting area</p>
        </div>
      </div>
    </div>
    <div class="col m-0 p-0">
      <div class="row m-0 p-0">
        <div class="col-5 col-sm-5 col-md-12 card-6 d-flex align-items-center img-6">
          <p class="card-text">Amazing Music</p>
        </div>
        <div class="col-7 col-sm-7 col-md-12 card-7 d-flex align-items-center img-7">
          <p class="card-text">Best Family night party</p>
        </div>
      </div>
    </div>
  </div>
<!-- -------------------------------------------------------- -->
<div class="container mt-5">
<div id="carouselRegisterVendor" class="carousel slide mt-5" data-ride="carousel">
  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="d-flex align-items-center justify-content-start" style="height: 400px; background-color: #fff6f8;">
        <div class="text-left ml-5">
          <h3 class="text-pink">If you are a vendor:</h3>
          <p class="text-pink">To boost your wedding service business</p>
          <a href="signup.php">
            <button type="button" style="background: rgb(2,0,36);background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 0%, rgba(0,212,255,1) 100%); color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px;">
              Register Here
            </button>
          </a>
        </div>
        <!-- Image on the right side -->
        <div class="d-flex justify-content-end flex-grow-1">
          <img src="images/vendor.jpg" alt="Vendor Image" style="max-height: 300px; object-fit: cover; border-radius: 10px;">
        </div>
      </div>
    </div>
  </div>
</div>

<div id="carouselRegisterCustomer" class="carousel slide mt-5" data-ride="carousel">
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active mb-5">
        <div class="d-flex align-items-center justify-content-start" style="height: 400px; background-color: #fff6f8;">
          <div class="text-left ml-5">
            <h3 class="text-pink">Plan Your Wedding Under your own preferrable budget!</h3>
            <p class="text-pink">For More,</p>
            <a href="signup.php">
          <button type="button" style="background: rgb(2,0,36);background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 0%, rgba(0,212,255,1) 100%); color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px;">
            Register Here
          </button>
        </a>
</div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include "assets/partials/footer.php" ?> 

<!-- Include Bootstrap JS (Optional if you don't use dropdown or other JS features) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
