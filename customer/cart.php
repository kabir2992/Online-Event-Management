<?php
include "header.php";
include "../database/db.php";

$user = $_SESSION['id'];
$venueId = isset($_GET['venue_id']) ? $_GET['venue_id'] : 0;

function redirectToCart()
{
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}

if ($venueId > 0) {

    $checkCartQuery = "SELECT * FROM tbl_cart_venue WHERE venue_id = $venueId AND user_id = $user";
    //echo $checkCartQuery;
    $checkCartResult = mysqli_query($conn, $checkCartQuery);

    if (mysqli_num_rows($checkCartResult) == 0) {
        $insertCartQuery = "INSERT INTO tbl_cart_venue(venue_id,user_id) VALUES ($venueId, $user)";
        if (mysqli_query($conn, $insertCartQuery)) {
            // Inserting services into cart_service
            $serviceQuery = "INSERT INTO tbl_cart_service(user_id,venue_service_id)
                             SELECT $user, venue_service_id FROM tbl_venue_services WHERE venue_id = $venueId";
            mysqli_query($conn, $serviceQuery);

            // redirectToCart();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // echo "Venue already in the cart.";
    }
}

if (isset($_GET['deletevid'])) {
    $venueIdToDelete = $_GET['deletevid'];

    // Delete venue from cart_venue
    $deleteVenueCartQuery = "DELETE FROM tbl_cart_venue WHERE venue_id = ? AND user_id = ?";

    $stmtDeleteVenueCart = mysqli_prepare($conn, $deleteVenueCartQuery);
    mysqli_stmt_bind_param($stmtDeleteVenueCart, "ii", $venueIdToDelete, $user);

    if (mysqli_stmt_execute($stmtDeleteVenueCart)) {
        // redirectToCart();
    } else {
        echo "Error deleting venue: " . mysqli_stmt_error($stmtDeleteVenueCart);
    }
}

if (isset($_GET['deletesid']) && isset($_GET["deletevnid"])) {
    $serviceIdToDelete = $_GET['deletesid'];
    $venueIdToDelete = $_GET['deletevnid'];

    $deleteServiceCartQuery = "DELETE FROM tbl_cart_service WHERE venue_service_id = ? AND user_id = ?";

    $stmtDeleteServiceCart = mysqli_prepare($conn, $deleteServiceCartQuery);
    mysqli_stmt_bind_param($stmtDeleteServiceCart, "ii", $serviceIdToDelete, $user);

    if (mysqli_stmt_execute($stmtDeleteServiceCart)) {
        echo "Service deleted successfully."; // Add this line for debugging
        // redirectToCart();
    } else {
        echo "Error deleting service: " . mysqli_stmt_error($stmtDeleteServiceCart);
    }
}


// removing data form cart
$sql = "DELETE FROM tbl_cart_venue WHERE user_id = $user AND venue_id != $venueId";
mysqli_query($conn, $sql);


$sql = "DELETE FROM tbl_cart_service WHERE user_id = $user AND venue_service_id NOT IN (SELECT venue_service_id FROM tbl_venue_services WHERE venue_id = $venueId)";
// echo $sql;
mysqli_query($conn, $sql);
?>

<form method='post' action=''>
    <div class="row" id="cart-container">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Cart</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="catable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <th>Category</th>
                                <th>Names</th>
                                <th>Prices</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                // Display Venue Information
                                $venueCartQuery = "SELECT v.venue_id, v.name, v.price
                                        FROM tbl_cart_venue cv
                                         JOIN tbl_venue v ON cv.venue_id = v.venue_id
                                         WHERE cv.user_id = $user AND cv.venue_id = $venueId";

                                //  echo $venueCartQuery;
                                
                                $venueCartResult = mysqli_query($conn, $venueCartQuery);

                                if ($venueCartResult) {
                                    $counter = 1;
                                    while ($venueRow = mysqli_fetch_assoc($venueCartResult)) {

                                        // echo '<pre>';
                                        // print_r($venueRow);
                                        // echo '</pre>';
                                
                                        echo "<tr>";
                                        echo "<td>
											<div class='d-flex'>
												<label class='mb-0'><input type='checkbox' id='select-checkbox-" . $counter . "' name='venueServiceIds[]' class='mx-2' onchange='updateThePrice(1," . $venueRow['venue_id'] . "," . $venueRow['price'] . "," . $counter . ");'/>
												 Venue</label>
											</div></td>";
                                        echo "<td>" . $venueRow['name'] . "</td>";
                                        echo "<td>" . $venueRow['price'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='cart.php?venue_id=" . $venueId . "&deletevid=" . $venueRow['venue_id'] . "' class='btn badge rounded-pill bg-label-danger me-1'>Remove</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                        $counter++;
                                    }
                                }
                                $totalPrice = 0;

                                $serviceCartQuery = "SELECT * 
                                FROM tbl_cart_service cs
                                JOIN tbl_venue_services s ON cs.venue_service_id = s.venue_service_id
                                WHERE cs.user_id = $user AND venue_id = $venueId";

                                $serviceCartResult = mysqli_query($conn, $serviceCartQuery);

                                if ($serviceCartResult) {
                                    while ($serviceRow = mysqli_fetch_assoc($serviceCartResult)) {

                                        // echo '<pre>';
                                        // print_r($serviceRow);
                                        // echo '</pre>';
                                
                                        $venueIdToDelete = $serviceRow['venue_id'];  // Declare $venueIdToDelete here
                                        echo "<tr>";
                                        echo "<td>
									<div class='d-flex'>
									<label class='mb-0'><input type='checkbox' id='select-checkbox-" . $counter . "' name='venueServiceIds[]' class='mx-2' onchange='updateThePrice(2," . $serviceRow['venue_service_id'] . "," . $serviceRow['price'] . "," . $counter . ");'/>
									Service</label>
									</div>
									</td>";
                                        echo "<td>" . $serviceRow['service_name'] . "</td>";
                                        echo "<td>" . $serviceRow['price'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='cart.php?venue_id=" . $venueId . "&deletevnid=" . $venueIdToDelete . "&deletesid=" . $serviceRow['venue_service_id'] . "' class='btn badge rounded-pill bg-label-danger me-1'>Remove</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                        $totalPrice += $serviceRow['price'];
                                        $counter++;
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total Price:
                                        <?php echo $totalPrice; ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="col-12" id="booked-error-mssg">
                
            </div>
        </div>
</form>

<div class="col-md-6">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Booking Details</h5>
        </div>
        <div class="card-body">
            <div class="form-floating form-floating-outline mb-4">
                <?php
                date_default_timezone_set("Asia/Kolkata");
                ?>
                <input class="form-control" type="date" name='start_date' id="start_date_input" min="" required>
                <label for="start_date_input">Start Date</label>
            </div>
            <div class="form-floating form-floating-outline mb-4">
                <input class="form-control" type="date" name='end_date' id="end_date_input"
                    min="<?php echo date("Y-m-d"); ?>" required>
                <label for="end_date_input">End Date</label>
            </div>


            <div class="mt-2 mb-3">
                <label for="from_time" class="form-label">From</label>
                <input type="time" id="from_time" name='fromTime' class="form-control">
            </div>
            <div class="mt-2 mb-3">
                <label for="to_time" class="form-label">To</label>
                <input type="time" id="to_time" name='toTime' class="form-control">
            </div>
            <div class="form-floating form-floating-outline mb-4">
                <input class="form-control" type="text" name='price' id="price_input" placeholder="Total Price"
                    readonly>
                <label for="price_input">Total Price</label>
            </div>
            <div class="mb-4 mx-1">
                <input class="" type="checkbox" name='terms&condition' id="termsCondition"
                    onchange="toggleCheckoutButton()" />
                <label class="d-inline-block">Agree to terms & conditions</label>
            </div>
            <button type="button" class="btn btn-primary waves-effect waves-light"
                onclick="checkout()">Checkout</button>
        </div>
    </div>
</div>
</div>
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>

    // =========================================================
    // function to verify that services is already booked or not

    var totalPrice = 0;
	var venueIdArray = [];
	var serviceIdArray = [];

    function updateThePrice(type, id, price, no) {
        var startDate = $("#start_date_input").val();
        var endDate = $("#end_date_input").val();
        var checkbox_id = "#select-checkbox-" + no;

        if (!(startDate == '' || endDate == '')) {
            var startDate2 = new Date(startDate);
            var endDate2 = new Date(endDate);
            var differenceMs = Math.abs(endDate2 - startDate2);
            var differenceDays = Math.ceil(differenceMs / (1000 * 60 * 60 * 24));

            checkForServiceAlreadyBooked(id, function (checkValue) {
                console.log("Check : " + checkValue);
                if (checkValue == 1) {

                    // display message
                    $("#booked-error-mssg").html('<div class="card">' +
                        '<div class="card-body">' +
                            '<div class="d-flex justify-content-center">' +
                                '<p class="mt-2" style="color: red;">Already Booked</p>' +
                            '</div>' +
                        '</div>' +
                    '</div>');

                    $(checkbox_id).prop('checked', false);
                    return;
                }

                if ($(checkbox_id).prop('checked')) {
                    var newTotalPrice = price * differenceDays;
                    totalPrice += newTotalPrice;

                    // insert id into array
                    if (type == 1) {
                        venueIdArray.push(id);
                    } else {
                        serviceIdArray.push(id);
                    }
                } else {
                    var newTotalPrice = price * differenceDays;
                    totalPrice -= newTotalPrice;

                    // remove id into array
                    if (type == 1) {
                        var index = venueIdArray.indexOf(id);
                        if (index !== -1) {
                            venueIdArray.splice(index, 1);
                        }
                    } else {
                        var index = serviceIdArray.indexOf(id);
                        if (index !== -1) {
                            serviceIdArray.splice(index, 1);
                        }
                    }
                }

                $("#price_input").val(totalPrice);
            });
        } else {
            alert("Select date first");
        }
    }

    function checkForServiceAlreadyBooked(id, callback) {
        var start_date = $("#start_date_input").val().trim();
        var end_date = $("#end_date_input").val().trim();
        var from_time = $("#from_time").val().trim();
        var to_time = $("#to_time").val().trim();

        $.ajax({
            url: 'check-service-booked.php',
            type: 'POST',
            data: {
                startDate: start_date,
                endDate: end_date,
                fromTime: from_time,
                toTime: to_time,
                serviceId: id
            },
            success: function (response) {
                callback(response);
            }
        });
    }


    $("#start_date_input, #end_date_input").change(function () {
        $("#price_input").val(0);
        $('input[name="venueServiceIds[]"]').prop('checked', false);
        venueIdArray.length = 0;
        serviceIdArray.length = 0;

        $("#booked-error-mssg").html('');
    });



    function checkout() {
        var totalPrice = $("#price_input").val();
        var termsConditionChecked = $("#termsCondition").prop("checked");

        if (termsConditionChecked && totalPrice > 0) {
            openRazorpay();
        } else {
            alert("Please agree to the terms & conditions and ensure a valid total price before proceeding to payment.");
        }
    }

    function openRazorpay() {
        var totalPrice = $("#price_input").val();
        var options = {
            "key":  "rzp_test_eZALc6p6A41qD2",
            "amount": totalPrice * 100,
            "currency": "INR",
            "name": "Be'Eventful",
            "description": "Make Your Event Beautiful with Be'Eventful",
            "image": "https://s3.amazonaws.com/rzp-mobile/images/rzp.jpg",
            "handler": function (response) {
                if (response.razorpay_payment_id) {
                    var pid = response.razorpay_payment_id;

                    var startDate = $("#start_date_input").val();
                    var endDate = $("#end_date_input").val();
                    var price = $("#price_input").val();

                    $.ajax({
                        type: "POST",
                        url: "AddBooking.php",
                        data: {
                            pid: pid,
                            startDate: startDate,
                            endDate: endDate,
                            price: price,
                            venueIds: venueIdArray,
                            serviceIds: serviceIdArray
                        },
                        success: function (data) {
                            // window.location.href = 'invoice.php';
                            // $("#cart-container").html(data);
                            alert("Your booking has been confirmed !");
                        },
                        error: function () {
                            alert("An error occurred while Paying and Add booking.");
                        }
                    });
                }
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }



    // =============================================

    // var blockedDates = ['2024-03-05', '2024-02-08', '2024-03-01']; // Array of blocked dates in yyyy-mm-dd format

    // $('#start_date_input').on('focus', function() {
    //     var currentDate = $(this).val();
    //     if (blockedDates.includes(currentDate)) {
    //         $(this).val(''); // Clear the input if the current value is a blocked date
    //     }
    // });

    // $('#start_date_input').on('change', function() {
    //     var selectedDate = $(this).val();
    //     if (blockedDates.includes(selectedDate)) {
    //         $(this).val(''); // Clear the input if the selected date is a blocked date
    //         alert('This date is blocked.');
    //     }
    // });

</script>

<?php
include "footer.php";
?>