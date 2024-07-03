<?php
include "header.php";
include "../database/db.php";
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();

if (isset($_POST['btn_venue'])) {

    function renameFileIfExists($folderPath, $fileName) {
        $newFileName = $fileName;
        $counter = 1;
    
        while (file_exists($folderPath . '/' . $newFileName)) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . $counter . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $counter++;
        }
    
        return $newFileName;
    }

    
    // Validate and sanitize user inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $file = $_FILES['FILE'];

    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';


    // Capture the vendor's ID
    $vendorID = $_SESSION['id'];

    // Validate the image
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    $uploadPath = "../images/venue_img/";

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (in_array($fileExtension, $allowedExtensions) && $file['size'] <= $maxFileSize) {
            // File is valid
            $newFileName = renameFileIfExists($uploadPath,$file['name']);
            $filePath = $uploadPath . $newFileName;

            // Start the transaction
            mysqli_begin_transaction($conn);

            try {
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    // Insert data into the database, including the vendor ID
                    $qry = "INSERT INTO tbl_venue (name, address, price, capacity, vendor_id, status) VALUES ('$name', '$address', $price, $capacity, '$vendorID', 0)";
                    if (mysqli_query($conn, $qry)) {
                        // Capture the last inserted venue ID
                        $venueID = mysqli_insert_id($conn);

                        $sql = "INSERT INTO tbl_venue_image(venue_id,image_name) VALUES('$venueID','$newFileName');";
                        mysqli_query($conn,$sql);

                        // Insert selected services, their names, and prices into the tbl_venue_services table
                        if (!empty($_POST['services']) && !empty($_POST['service_prices'])) {
                            $services = $_POST['services'];
                            $servicePrices = $_POST['service_prices'];
                           

                            // Retrieve service names from the tbl_service table
                            $serviceQuery = "SELECT service_id, service_name FROM tbl_service";
                            $serviceResult = mysqli_query($conn, $serviceQuery);
                            $serviceData = array();

                            while ($serviceRow = mysqli_fetch_assoc($serviceResult)) {
                                $serviceData[$serviceRow['service_id']] = $serviceRow['service_name'];
                            }

                            // for ($i = 0; $i < count($services); $i++) {
                            //     $serviceID = mysqli_real_escape_string($conn, $services[$i]);
                            //     $price = ($_POST['service_prices'][$i] !== '' && is_numeric($_POST['service_prices'][$i])) ? $_POST['service_prices'][$i] : 0;
                            //     $quantity = ($_POST['service_quantities'][$i] !== '' && is_numeric($_POST['service_quantities'][$i])) ? $_POST['service_quantities'][$i] : 0; // Added this line
                            //     $serviceName = $serviceData[$serviceID];

                            //     //echo "Price: ".$price."Price 2: ".$_POST['service_prices'][$i]."<br>";

                            //     // Check if the price and quantity are not empty and are valid numeric values
                            //     if ($price !== '' && is_numeric($price) && $quantity !== '' && is_numeric($quantity)) {
                            //         $insertServiceQuery = "INSERT INTO tbl_venue_services (venue_id, service_id, service_name, prize, Qty) VALUES ($venueID, $serviceID, '" . mysqli_real_escape_string($conn, $serviceName) . "', $price, $quantity)";

                            //         if (!mysqli_query($conn, $insertServiceQuery)) {
                            //             // Output the error and exit the script
                            //             die('Service Insertion Error: ' . mysqli_error($conn) . '<br>Query: ' . $insertServiceQuery);
                            //         }
                            //     } else {
                            //         // Handle the case where the price or quantity is empty or not a valid numeric value
                            //         echo "Invalid price or quantity for service: $serviceName<br>";
                            //     }
                            // }
                        }

                        // Commit the transaction
                        mysqli_commit($conn);

                        // Redirect after a successful insert to avoid duplicate submissions
                        // header("Location: venue.php");
                        exit;
                    } else {
                        echo "<script>alert('Not inserted! Database Error.');</script>";
                    }
                } else {
                    echo "<script>alert('File upload failed. Please try again.');</script>";
                }
            } catch (Exception $e) {
                // An error occurred, rollback the transaction
                mysqli_rollback($conn);
                $error_message = $e->getMessage();
                $error_line = $e->getLine();
                error_log("Error: $error_message on line $error_line");
                echo $error_message."<br>";
                echo $error_line();
                echo "<script>alert('An error occurred. Please try again.');</script>";
            }
            
        } else {
            echo "<script>alert('Invalid image format or size. Please upload a valid image (jpg, jpeg, png) up to 2MB.');</script>";
        }
    } else {
        echo "<script>alert('File upload error. Please try again.');</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Venue Management</title>
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css">
    <style>
        #vtable img {
            width: 80px; /* Increase the image width */
            height: 80px; /* Increase the image height */
        }

        .services-table {
            border-collapse: collapse;
            width: 68%;
        }

        .services-table th,
        .services-table td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        .services-table label {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <h4 class="py-3 mb-4">Venue</h4>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name='name' class="form-control" id="basic-default-fullname" placeholder="" required />
                            <label for="basic-default-fullname">Name</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-100" name='address' id="exampleFormControlTextarea1" placeholder="Write here..." required></textarea>
                            <label for "exampleFormControlTextarea1">Address</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" name='price' class="form-control" id="basic-default-company" placeholder="" required />
                            <label for="basic-default-company">Price</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" name='capacity' class="form-control" id="basic-default-company" placeholder="" required />
                            <label for="basic-default-company">Capacity</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <p>Services you provide:</p>
                            <table class="services-table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Price</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
            <?php
            // Retrieve service names from the tbl_service table
            $serviceQuery = "SELECT service_id, service_name FROM tbl_service";
            $serviceResult = mysqli_query($conn, $serviceQuery);
            while ($serviceRow = mysqli_fetch_assoc($serviceResult)) {
                $serviceID = $serviceRow['service_id'];
                $serviceName = $serviceRow['service_name'];

                // Check if the service is one of the specified services (rooms, chairs, servants)
              
                    echo "<tr class='service-row'>";
                    echo "<td>";
                    echo "<label>";
                    echo "<input type='checkbox' name='services[]' value='$serviceID' /> $serviceName";
                    echo "</label>";
                    echo "</td>";
                    echo "<td><input type='number' name='service_prices[]' placeholder='price' /></td>";
                    if ($serviceName == 'Rooms' || $serviceName == 'Chairs' || $serviceName == 'Servents') {

                    echo "</tr>";
                }
            }
            ?>
        </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image</label>
                            <input class="form-control" name='FILE' type="file" id="formFile" required />
                        </div>
                        <?php
                        $vendorID = $_SESSION['id']; // Assuming you have stored the vendor's ID in the session.
                        echo "<input type='hidden' name='vid' value='$vendorID'>";
                        ?>
                        <button type="submit" name='btn_venue' class="btn btn-primary" value="saveBtn">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <?php
                        $qry = "SELECT v.venue_id, v.name, v.address, v.price, v.capacity,  v.status, 
                        GROUP_CONCAT(CONCAT(vs.service_name, ' ($', vs.price, ')') SEPARATOR ', ') AS services
                        FROM tbl_venue v
                        LEFT JOIN tbl_venue_services vs ON v.venue_id = vs.venue_id
                        WHERE v.vendor_id = '$vendorID'
                        GROUP BY v.venue_id";

                        $result = mysqli_query($conn, $qry);
                        ?>
                        <table id="vtable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Services</th>
                                    <th>Total Prize</th> 
                                    <th>Capacity</th>
                                 
                                    <th>Actions</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['address'] . "</td>";
                                    $totalPrice = $row['price'];
                                    $servicePriceQuery = "SELECT SUM(price) as total FROM tbl_venue_services WHERE venue_id = " . $row['venue_id'];
                                    $servicePriceResult = mysqli_query($conn, $servicePriceQuery);
                                    $serviceTotal = mysqli_fetch_assoc($servicePriceResult)['total'];
                                    $totalPrice += $serviceTotal;
                                    echo "<td>" . $row['services'] . "</td>";
                                    echo "<td>" . $totalPrice . "</td>";
                                    echo "<td>" . $row['capacity'] . "</td>";
                                    //  echo "<td><img src='" . $row['image_path'] . "' width='80' height='80'></td>";
                                    echo "<td><a href='update_venue.php?id=" . $row['venue_id'] . "' class='badge rounded-pill bg-label-warning me-1'>Edit</a></td>";
                                    echo "<td><span class='badge rounded-pill bg-label-primary me-1'>" . ($row['status'] == 1 ? 'Active' : 'Pending') . "</span></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include "footer.php";
    ?>
    <script src="assets/DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#vtable').DataTable();
        });
    </script>
</body>
</html>
