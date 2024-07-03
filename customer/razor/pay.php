<?php
require_once 'config.php';
require_once 'razorpay-php/Razorpay.php';
session_start();


$price=$_SESSION['PRICE'];

use Razorpay\Api\Api;
$api = new Api($keyId, $keySecret);

$orderData = [
    'receipt'         => 'rcptid_11',
    'amount'          => $price."00", // 39900 rupees in paise
    'currency'        => 'INR'
];

$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'automatic';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
{
    $checkout = $_GET['checkout'];
}

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $_SESSION['name'],
    "description"       => "",
    "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
    "prefill"           => [
    "name"              => "Daft Punk",
    "email"             => "customer@merchant.com",
    "contact"           => "9999999999",
    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

require("./checkout/{$checkout}.php");

// if ($razorpayResponse && isset($razorpayResponse['status']) && $razorpayResponse['status'] === 'paid') {
//     header("Location: verify.php");
//     exit();
// } else {
//     echo "Payment failed. Please try again.";
// }

?>