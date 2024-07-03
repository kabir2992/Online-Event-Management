<?php
require('config.php');
include "../../database/db.php";

session_start();

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false) {
    $api = new Api($keyId, $keySecret);

    try {
        $attributes = array(
            'razorpay_order_id'     => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id'   => $_POST['razorpay_payment_id'],
            'razorpay_signature'    => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true) {
    $html = "<p>Your payment was successful</p>
             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";

    $insertQuery = "INSERT INTO tbl_booking VALUES(DEFAULT, ".$_SESSION['id'].", ".$_SESSION['vdid'].", ".$_SESSION['vnid'].", '".$_SESSION['start_date']."', '".$_SESSION['end_date']."' ,'".$_SESSION['time']."', default, default, ".$_SESSION['PRICE'].")";

    $result = mysqli_query($conn, $insertQuery);

    if ($result) {
        $last_id = mysqli_insert_id($conn);
        mysqli_query($conn,"INSERT INTO tbl_payment_log VALUES(DEFAULT, '".$_POST['razorpay_payment_id']."', $last_id, ".$_SESSION['id'].", ".$_SESSION['vnid'].", ".$_SESSION['vdid']." , default, ".$_SESSION['PRICE'].", 1, 0);");

        mysqli_query($conn, "DELETE FROM tbl_cart WHERE cid=".$_SESSION['id'].";");
        mysqli_query($conn, "DELETE FROM tbl_cart_venue WHERE cid=".$_SESSION['id'].";");

        //echo $last_id;
        //echo "<br>";
        //echo "INSERT INTO tbl_payment_log VALUES(DEFAULT, '".$_POST['razorpay_payment_id']."', $last_id, ".$_SESSION['id'].", ".$_SESSION['vnid'].", ".$_SESSION['vdid']." , default, ".$_SESSION['PRICE'].", 1, 0);";

       echo "<script>window.location.href='/ems/customer/';</script>";
    } else {
        echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn)."<br>";
        var_dump($_SESSION['vdid']);
        var_dump($_SESSION['id']);

    }
} else {
    mysqli_query($conn, "INSERT INTO tbl_payment_log VALUES(default,NULL,NULL,".$_SESSION['id'].",default,1);");
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;
?>

