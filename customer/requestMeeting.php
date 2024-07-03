<?php
// Include database connection file
include "../database/db.php";


use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input fields
    $meetingDate = mysqli_real_escape_string($conn, $_POST['meetingDate']);
    $meetingTime = mysqli_real_escape_string($conn, $_POST['meetingTime']);
    $meetingAgenda = mysqli_real_escape_string($conn, $_POST['meetingAgenda']);

    // Fetch venue details and vendor's email
    $venueId = mysqli_real_escape_string($conn, $_POST['venueId']);
    $venueQuery = "SELECT * FROM tbl_venue WHERE venue_id = $venueId";
    $venueResult = mysqli_query($conn, $venueQuery);

    if ($venueResult && $venue = mysqli_fetch_assoc($venueResult)) {
        // Fetch vendor's email from tbl_user using vendor_id
        $vendorId = $venue['vendor_id'];
        $vendorEmailQuery = "SELECT email FROM tbl_user WHERE user_id = $vendorId";
        $vendorEmailResult = mysqli_query($conn, $vendorEmailQuery);

        if ($vendorEmailResult && $vendorEmailRow = mysqli_fetch_assoc($vendorEmailResult)) {
            $vendorEmail = $vendorEmailRow['email'];

            // Fetch user's email (assuming user is logged in and email is stored in session)
            $user = $_SESSION['id']; // You need to replace this with your actual session variable

            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'beeventful16@gmail.com'; // SMTP username
            $mail->Password = 'ddyppyutwqgxsanf'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Set email parameters
            $mail->setFrom('from@example.com', 'Be-Eventful');
            $mail->addAddress($vendorEmail); // Add vendor's email address
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = "Meeting Request for Venue: " . $venue['name'];
            $mail->Body = "
                <p>Hello,</p>
                <p>You have received a meeting request for your venue. Details are as follows:</p>
                <ul>
                    <li><strong>Meeting Date:</strong> $meetingDate</li>
                    <li><strong>Meeting Time:</strong> $meetingTime</li>
                    <li><strong>Meeting Agenda:</strong> $meetingAgenda</li>
                    <li><strong>User Email:</strong> $user</li>
                </ul>
                <p>Please take necessary action accordingly.</p>
                <p>Regards,<br>Be'Eventful</p>
            ";

            // Send email
            if ($mail->send()) {
                $insertQuery = "INSERT INTO tbl_meeting_requests (venue_id, user_id, meeting_date, meeting_time, meeting_agenda) 
                VALUES ('$venueId', '$user', '$meetingDate', '$meetingTime', '$meetingAgenda')";

            if(mysqli_query($conn, $insertQuery)) {
    // Meeting request details inserted successfully

    // JavaScript to display alert and redirect
    echo '<script>alert("Your request for a virtual meeting has been sent to the vendor. You\'ll be catching up with them soon. Keep patience. Thank you.");';
    // Redirect to the current page after showing the alert
    echo 'window.location.href = "book_venue.php?id='.$venueId.'";</script>';
    exit();                     
            } } else {
                // Email sending failed
                echo "Error: " . $mail->ErrorInfo;
            }
        } else {
            // Vendor email not found
            echo "Vendor email not found!";
        }
    } else {
        // Venue not found
        echo "Venue not found!";
    }
} else {
    // Redirect if accessed without POST request
    exit();
}
?>
