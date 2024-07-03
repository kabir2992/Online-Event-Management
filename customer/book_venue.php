<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Venue</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/image-gallery.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            margin-top: 50px;
        }

        img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 20px;
            color: #6c757d;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        strong {
            color: #495057;
        }

        .venue-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #map {
            height: 400px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .address-square {
            width: 100px;
            height: 100px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            line-height: 100px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php

        // creating a function to show images
        function getImageGallery($number,$imageArray,$hideOrShow) {
            $displays = '';
            if ($hideOrShow) {
                $displays = ' rest-hidden-images hide-extra-image';
            }
          
            if ($number == 1) {
                return '<div class="col-md-12 px-0 mt-2 single-image-class'.$displays.'">
                        <img src="http://localhost/ems/images/venue_img/'.$imageArray[0].'" class="image-venues image-number-1" alt="Image 1">
                    </div>';
            } else if ($number == 2) {
                return '<div class="col-md-6 mt-1'.$displays.'">
                        <img src="http://localhost/ems/images/venue_img/'.$imageArray[0].'" class="image-venues two-image-views" id="" alt="Image 1">
                    </div>
                    <div class="col-md-6 mt-1'.$displays.'">
                        <img src="http://localhost/ems/images/venue_img/'.$imageArray[1].'" class="image-venues two-image-views" id="" alt="Image 1">
                    </div>';

            } else if ($number == 3) {
                return '<div class="col-md-6 px-0 mt-2'.$displays.'">
                        <img src="http://localhost/ems/images/venue_img/'.$imageArray[0].'" class="image-venues image-number-1" alt="Image 1">
                    </div>
                    <div class="col-md-6 px-2 mt-2'.$displays.'">
                        <div class="row">
                            <div class="col-md-12">
                                <img src="http://localhost/ems/images/venue_img/'.$imageArray[1].'" class="image-venues image-number-3" id="" alt="Image 1">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <img src="http://localhost/ems/images/venue_img/'.$imageArray[2].'" class="image-venues image-number-4" id="" alt="Image 1">
                            </div>
                        </div>
                    </div>';
            } else {
                return '<div class="col-md-6 px-0 mt-2'.$displays.'">
                        <img src="http://localhost/ems/images/venue_img/'.$imageArray[0].'" class="image-venues image-number-1" alt="Image 1">
                    </div>
                    <div class="col-md-6 px-2 mt-2'.$displays.'">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="http://localhost/ems/images/venue_img/'.$imageArray[1].'" class="image-venues image-number-2" id="" alt="Image 1">
                            </div>
                            <div class="col-md-6">
                                <img src="http://localhost/ems/images/venue_img/'.$imageArray[2].'" class="image-venues image-number-3" id="" alt="Image 1">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <img src="http://localhost/ems/images/venue_img/'.$imageArray[3].'" class="image-venues image-number-4" id="" alt="Image 1">
                            </div>
                        </div>
                    </div>';
            }

        }


        include "../database/db.php";
        include "header.php";

        $venueId = isset($_GET['id']) ? $_GET['id'] : null;
        $venueQuery = "SELECT * FROM tbl_venue WHERE venue_id = $venueId";
        $venueResult = mysqli_query($conn, $venueQuery);

        if ($venueResult && $venue = mysqli_fetch_assoc($venueResult)) {
            $totalPrice = $venue['price'];
            $servicePriceQuery = "SELECT SUM(price) as total FROM tbl_venue_services WHERE venue_id = " . $venue['venue_id'];
            $servicePriceResult = mysqli_query($conn, $servicePriceQuery);
            $serviceTotal = mysqli_fetch_assoc($servicePriceResult)['total'];
            $totalPrice += $serviceTotal;

            $venueServicesQuery = "SELECT * FROM tbl_venue_services WHERE venue_id = " . $venue['venue_id'];
            $venueServicesResult = mysqli_query($conn, $venueServicesQuery);
            ?>

            <div class="row">
                <div class="col-md-8 scrollable-content">
                    <div class="image-modal" id="modal">
                        <div class="modal-overlay" id="modal-overlay"></div>
                        <span class="cancel-btn text-black" id="cancel-btn">
                            <i class="mdi mdi-close text-white"></i>
                        </span>
                        <img src="" alt="Zoomed Image" class="modal-image" id="modal-image">
                    </div>
                    
                    <div class="main-gallery-container row">
                        <?php 
                        // fetching images

                        $image_display_container = '';
                        $sql = "SELECT image_name FROM tbl_venue_image WHERE venue_id = ?";

                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param('i', $venueId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            $number_of_images = $result->num_rows;

                            if ($number_of_images > 0) {
                                $images_main_array = array();
                                while ($row = $result->fetch_assoc()) {
                                    $images_main_array[] = $row["image_name"];
                                }


                                // finding image gallery mode
                                if ($number_of_images > 4) {
                                    $counter = ceil($number_of_images/4);
                                    $counter2 = $number_of_images; 
                                    $counter3 = $number_of_images;
                                    $counter4 = $number_of_images;
                                    $counter5 = 0;
                                    $image_secondary_array = array();

                                    for ($i = 1; $i <= $counter; $i++) {
                                        for ($j = 0; $j < $counter3; $j++) {
                                            if ($j == 4) {
                                                break;
                                            }
                                            $image_secondary_array[] = $images_main_array[$counter5];
                                            $counter5++;
                                        }

                                        if ($i == 1) {
                                            $image_display_container .= getImageGallery($counter3,$image_secondary_array,0);
                                        } else {    
                                            $image_display_container .= getImageGallery($counter3,$image_secondary_array,1);
                                        }
                                        
                                        unset($image_secondary_array);
                                        $counter3 = $counter3 - 4;
                                    }
                                    $image_display_container .= '<div class="col-md-12 mb-1">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn view-more-image-btn mt-2" id="view-more-image">View more</button>
                                        </div>
                                    </div>';

                                    echo $image_display_container;
                                } else {
                                    $image_display_container = getImageGallery($number_of_images,$images_main_array,0);
                                }
                            }

                            $result->free();
                            $stmt->close();
                        }
                    
                        ?>
                    </div>
                </div>

                <div class="col-md-4 fixed-column">
                    <div class="main-venue-info-card">
                        <div class="d-flex justify-content-between">
                            <h2><?php echo $venue['name']; ?></h2>
                            <!-- <span class="mdi mdi-cart-variant text-dark"></span> -->
                            <span class="mdi mdi-chat-processing-outline" style="color: blue; font-size: 18px; cursor: pointer;" onclick="directToChatting(1);"></span>
                        </div>

                        <div>
                            <p style="font-size: 15px;">
                                <span class="mdi mdi-map-marker-radius"></span> <?php echo $venue['address']; ?>
                            </p>
                        </div>

                        <div class="venue-details venue-info-content">
                            <p>
                                <span class="mdi mdi-account-group"></span> Capacity <span class="text-dark"><?php echo $venue['capacity']; ?></span>
                            </p>
                            <p>
                                <span class="mdi mdi-currency-rupee"></span> Total Amount <span class="text-dark">&#8377;<?php echo $venue['price']; ?></span>
                            </p>
                            <p class="mb-1">
                                <?php
                                if ($venueServicesResult && mysqli_num_rows($venueServicesResult) > 0) {
                                    echo '<span class="mdi mdi-room-service-outline"></span> Services
                                    <span>';
                                    
                                    echo "<ul class='service-list-items'>";
                                    while ($service = mysqli_fetch_assoc($venueServicesResult)) {
                                        echo "<li>{$service['service_name']} - {$service['price']}</li>";
                                        $totalPrice += $service['price'];
                                    }
                                    echo "</ul>";
                                } else {
                                    echo '<span class="mdi mdi-room-service-outline"></span> Services <strong>N/A</strong>
                                    <span>';
                                }
                                ?>
                                </span>
                            </p>
                        </div>

                        <div class="col-12">
                            <a href="cart.php?venue_id=<?php echo $venueId; ?>" class="btn btn-primary mt-3 book-now-button">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        } else {
            echo "<p>Venue not found.</p>";
        }
        ?>
    </div>

    <div class="col-md-8">
        <div id="map"></div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            function geocodeAndDisplayMap(address) {
                var apiKey = 'a2e8c24478bc460baf51fc8f065fba59';
                var geocodingUrl = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(address)}&key=${apiKey}`;

                fetch(geocodingUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results && data.results.length > 0) {
                            var location = data.results[0].geometry;

                            var map = L.map('map').setView([location.lat, location.lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                            L.marker([location.lat, location.lng]).addTo(map)
                                .bindPopup('Venue Location: ' + address)
                                .openPopup();
                        } else {
                            console.error('Geocoding API request failed.');
                        }
                    })
                    .catch(error => {
                        console.error('Error during geocoding:', error);
                    });
            }

            geocodeAndDisplayMap('<?= $venue['address'] ?>');
        </script>
    </div>

    <div class="col-md-8 mt-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <img src="assets/img/video.png" alt="Video Greet" class="video-call-image mt-1">
                    <div class="mx-3">
                        <p class="text-dark mb-0">Would you like to request a virtual meet?</p>
                        <a href="#" class="text-danger" id="requestMeetLink" data-toggle="modal" data-target="#meetingRequestModal">Request meet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modal!-->
    
    <div class="modal fade" id="meetingRequestModal" tabindex="-1" role="dialog" aria-labelledby="meetingRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="meetingRequestModalLabel">Request a Virtual Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="meetingRequestForm" method="POST" action="requestMeeting.php">
                    <div class="form-group">
                        <label for="meetingDate">Meeting Date:</label>
                        <input type="date" class="form-control" id="meetingDate" name="meetingDate" required>
                    </div>
                    <div class="form-group">
                        <label for="meetingTime">Meeting Time:</label>
                        <input type="time" class="form-control" id="meetingTime" name="meetingTime" required>
                    </div>
                    <div class="form-group">
                        <label for="meetingAgenda">Agenda:</label>
                        <textarea class="form-control" id="meetingAgenda" name="meetingAgenda" rows="3" required></textarea>
                    </div><br>
                    <input type="hidden" name="venueId" value="<?php echo trim($_GET["id"]);?>">
                    <button type="submit" class="btn btn-primary d-grid w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script>
        // images code
        $(document).on("click",".image-venues",function() {
            var imageUrl = $(this).attr("src");
            $("#modal-image").attr("src", imageUrl);
            $("#modal").css("display", "flex");
        });

        $(document).on("click","#cancel-btn",function() {
            $("#modal").css("display", "none");
        });

        $(document).on("click","#modal-overlay",function(event) {
            if (event.target.id === "modal-overlay") {
                $("#modal").css("display", "none");
            }
        });


        // view more image button
        var counter = 0;
        $("#view-more-image").click(function() {
            $(".rest-hidden-images").toggleClass("hide-extra-image");
            if (counter == 0) {
                $("#view-more-image").text("View Less");
                counter = 1;
            } else {
                $("#view-more-image").text("View More");
                counter = 0;
            }
        });

        // redirect method
        function directToChatting(id) {
            window.location.href = "chat.php?id="+id;
        }
    </script>
</body>

</html>