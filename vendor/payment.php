<?php
include "header.php";
include "../database/db.php";
@session_start();
$vendor_id = $_SESSION["id"];
?>

<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4">View Payment</h4>

<div class="card p-2">
    <div class="table-responsive text-nowrap">
        <?php
        $qry = "
            SELECT *
            FROM tbl_vendor_payment
            ;
        ";

        $result = mysqli_query($conn, $qry);

        if ($result) {
            echo '<table id="vtable" class="table table-striped table-bordered" style="width:100%">';
            echo '<thead>';
            echo '<th>Payment ID</th>';
            echo '<th>Booking ID</th>';
            echo '<th>Date & Time</th>';
            echo '<th>Total Amount</th>';
           
            echo '</thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['payment_id'] . "</td>";
                echo "<td>" . $row['booking_id'] . "</td>";
                echo "<td>" . $row['date_time'] . "</td>";
                echo "<td>" . $row['total_amount']. "</td>";
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
