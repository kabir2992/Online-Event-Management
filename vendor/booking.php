<?php
include "header.php";
include "../database/db.php";
?>
<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4">Booking</h4>
<div class="card p-2">
    <div class="table-responsive text-nowrap">
<?php
$qry = "
    SELECT tu.name AS uname,email,contact,tv.name AS vname,tv.address,start_date,end_date,booking_date FROM tbl_booking tb, tbl_venue tv, tbl_user tu, tbl_venue_book tvb
    WHERE 
    tb.user_id = tu.user_id AND
    tvb.booking_id = tb.booking_id AND
    tv.venue_id = tvb.venue_id AND
    tv.vendor_id = " . $_SESSION['id'] . ";";

$result = mysqli_query($conn, $qry);

if ($result) {
    echo '<table id="vtable" class="table table-striped table-bordered" style="width:100%">';
    echo '<thead>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Contact</th>';
    echo '<th>Venue Title</th>';
    echo '<th>Venue Address</th>';
    echo '<th>Booking start Date</th>';
    echo '<th>Booking end Date</th>';
    echo '<th>Order Placed On</th>';
    echo '</thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['uname'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['contact'] . "</td>";
        echo "<td>" . $row['vname'] . "</td>";
        echo "<td>" . $row['address'] . "</td>";
        echo "<td>" . $row['start_date'] . "</td>";
        echo "<td>".  $row['end_date'] . "</td>";
        echo "<td>" . $row['booking_date'] . "</td>";
        echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo "Query Error: " . mysqli_error($conn);
}
?>
</div>
</div>
<?php
include "footer.php";
?>
<script src="assets/DataTables/datatables.min.js"></script>
<script>
    new DataTable("#vtable");
</script>

<?php
if (isset($_POST['status'])) {
    $status = $_POST['status'];
    $user_id = $_POST['uid'];

    if ($status == 1) {
        $status = 0;
    } else {
        $status = 1;
    }
    $qry = "UPDATE tbl_booking SET order_status=$status WHERE id=$user_id";
    if (mysqli_query($conn, $qry)) {
        // Successfully updated status
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
}
?>
