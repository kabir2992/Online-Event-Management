
<?php
session_start();
include "../database/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data sent via Ajax

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $pid = trim($_POST["pid"]);
    $startDate = trim($_POST['startDate']);
    $endDate = trim($_POST['endDate']);   
    $price = trim($_POST['price']);
    $fromTime = trim($_POST['fromTime']);
    $toTime = trim($_POST['toTime']);


    $venueIdArray = $_POST["venueIds"];
    $serviceIdArray = $_POST["serviceIds"];

    if (COUNT($venueIdArray) > 0 || COUNT($serviceIdArray) > 0) {

        // Insert into tbl_booking
        $insertQuery = "INSERT INTO tbl_booking(user_id,start_date,end_date,from_time,to_time,total_amount) VALUES (?,?,?,?,?,?)";

        $lastInsertId = 0;
        if ($stmt = $conn->prepare($insertQuery)) {
            $stmt->bind_param("issssd",$_SESSION['id'],$startDate,$endDate,$fromTime,$toTime,$price);
            $stmt->execute();
            $lastInsertId = $stmt->insert_id;
            $stmt->close();
        }

        echo $lastInsertId;

        if ($lastInsertId > 0) {

            if (COUNT($venueIdArray) > 0) {
                $insertQuery2 = "INSERT INTO tbl_venue_book(booking_id,venue_id) VALUES (?,?);";

                if ($stmt = $conn->prepare($insertQuery2)) {
                    $stmt->bind_param("ii",$lastInsertId,$venueId);
                    $venueId = trim($venueIdArray[0]);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            if (COUNT($serviceIdArray) > 0) {
                foreach ($serviceIdArray as $value) {
                    $insertQuery3 = "INSERT INTO tbl_service_book(booking_id,service_id) VALUES (?,?);";

                    if ($stmt = $conn->prepare($insertQuery3)) {
                        $stmt->bind_param("ii",$lastInsertId,$value_id);
                        $value_id = trim($value);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }

            // insert data into payment table
            $insertQuery4 = "INSERT INTO tbl_customer_payment(payment_id,booking_id,total_amount) VALUES (?,?,?)";

            if ($stmt = $conn->prepare($insertQuery4)) {
                $stmt->bind_param("sid",$pid,$lastInsertId,$price);
                $stmt->execute();
                $stmt->close();
            }
        }

        mysqli_query($conn, "DELETE FROM tbl_cart_service WHERE user_id=".$_SESSION['id'].";");
        mysqli_query($conn, "DELETE FROM tbl_cart_venue WHERE user_id=".$_SESSION['id'].";");
    }
}
?>
