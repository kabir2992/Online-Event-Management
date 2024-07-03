<?php
include "header.php";
include "../database/db.php";


@session_start();

// Retrieve all venues from the database
$qry="SELECT 
    v.venue_id, 
    v.name, 
    v.address, 
    v.price AS venue_price, 
    v.capacity, 
    v.vendor_id,  
    v.status, 
    GROUP_CONCAT(vs.service_name SEPARATOR ', ') AS services,
    MIN(vi.image_name) AS image_path                                          
FROM 
    tbl_venue v
LEFT JOIN 
    tbl_venue_services vs ON v.venue_id = vs.venue_id
LEFT JOIN 
    tbl_venue_image vi ON v.venue_id = vi.venue_id
GROUP BY 
    v.venue_id, v.name, v.address, v.price, v.capacity, v.vendor_id, v.status;
";
$result = mysqli_query($conn, $qry);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Venue List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .venue-image {
            height: 200px;
            object-fit: cover;
        }

        .card-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card {
            box-shadow: 0 4px 8px rgba(138, 43, 226, 0.2);
        }

        .custom-button {
            background-color: #8A2BE2;
            border-color: #8A2BE2;
        }

        .custom-button:hover {
            background-color: #6A1B9A;
            border-color: #6A1B9A;
        }

        .price-filter-input {
            width: 150px !important;
        }
    </style>
</head>

<body>


<!-- <h4 class="py-3 mb-2">Venues</h4> -->

    <div class="container mt-2 px-0">
       
        <div class="mb-3 d-flex justify-content-between">
            <!-- Add the minimum and maximum input fields -->
            <div class="d-flex">
                <!-- <label for="min">Minimum Price:</label> -->
                <input type="text" id="min" name="min" class="form-control price-filter-input" placeholder="Minimum Price">
                <!-- <label for="max">Maximum Price:</label> -->
                <input type="text" id="max" name="max" class="form-control price-filter-input mx-2" placeholder="Maximum Price">
                <!-- Add "Apply Filters" button -->
                <button class="btn btn-primary mt-auto custom-button" onclick="applyFilters()">Apply Filters</button>
            </div>

            <div class="d-flex">
                <input type="text" id="search" name="search" class="form-control price-filter-input mx-2" placeholder="Search here...">
                <button class="btn btn-primary mt-auto custom-button" onclick="performSearch()">Search</button>
            </div>
        </div>
    </div>

        <div class="row d-flex" id="venueList">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                // Calculate the total price including venue and service prices
                $totalPrice = $row['venue_price'];
                $servicePriceQuery = "SELECT SUM(price) as total FROM tbl_venue_services WHERE venue_id = " . $row['venue_id'];
                $servicePriceResult = mysqli_query($conn, $servicePriceQuery);
                $serviceTotal = mysqli_fetch_assoc($servicePriceResult)['total'];
                $totalPrice += $serviceTotal;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                    <img src="../images/venue_img/<?= $row['image_path']?>" class="card-img-top venue-image" alt="<?= $row['name'] ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center"><?= $row['name'] ?></h5>
                            <p class="card-text capacity">&#128101; Capacity: <?= $row['capacity'] ?></p>
                            <p class="card-text services">&#127976; Services: <?= $row['services'] ?></p>
                            <p class="card-text address">&#127968; Address: <?= $row['address'] ?></p>
                            <p class="card-text total-price">&#128176; Total Price: <?= $totalPrice ?></p>
                           <?php echo "<a href='book_venue.php?id=".$row['venue_id']."' class='btn btn-primary mt-auto custom-button'>Book Now</a>"; ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function applyFilters() {
            filterVenues();
        }

        function performSearch() {
            filterVenues();
        }

        function filterVenues() {
            const minEl = document.getElementById('min');
            const maxEl = document.getElementById('max');
            const searchEl = document.getElementById('search');
            const venueList = document.getElementById('venueList');

            const min = parseFloat(minEl.value) || 0;
            const max = parseFloat(maxEl.value) || Infinity;
            const search = searchEl.value.toLowerCase();

            const venues = venueList.getElementsByClassName('col-md-4');
            for (const venue of venues) {
                const cardTitle = venue.querySelector('.card-title');

                if (!cardTitle) {
                    console.error('Card title not found:', venue);
                    continue;
                }

                const venueName = cardTitle.textContent.toLowerCase();

                // Check if the venue name contains the search text
                const isSearchMatch = venueName.includes(search);

                const totalPriceText = venue.querySelector('.total-price').textContent.split(':')[1];

                if (!totalPriceText) {
                    console.error('Total price text not found:', venue);
                    continue;
                }

                const totalPrice = parseFloat(totalPriceText.trim());

                if (!isNaN(totalPrice) && isSearchMatch && totalPrice >= min && totalPrice <= max) {
                    venue.classList.remove('d-none');
                } else {
                    venue.classList.add('d-none');
                }
            }
        }
        
    </script>

<?php
include 'footer.php';

?> 
</body>
</html>

