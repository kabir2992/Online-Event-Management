<?php
include "header.php";
include "../database/db.php";
@session_start();
$user = $_SESSION["id"];
?>
<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4">Payment</h4>
<div class="card p-2">
    <div class="table-responsive text-nowrap">
<?php
    $qry = "
        SELECT 
            b.user_id,
            cp.*
        FROM 
            tbl_booking b
        JOIN 
            tbl_customer_payment cp ON b.booking_id = cp.booking_id;
";
    $result = mysqli_query($conn,$qry);

?>
<table id="vtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <th>Payment ID</th>
        <th>Booking ID</th>
        <th>User ID</th>
        <th>Date & Time</th>
        <th>Total Amount</th>
        <th>Status</th>
    </thead>
    <tbody>
<?php
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$row['payment_id']."</td>";
        echo "<td>".$row['booking_id']."</td>";
        echo "<td>".$row['user_id']."</td>";
        echo "<td>".$row['datetime']."</td>";
        echo "<td>".$row['total_amount']."</td>";
        echo "<td>";
            if($row['vendor_status'] == 1){
                
                echo "<button  class='btn badge rounded-pill bg-label-success me-1'>Done</button>";
            } else {
                echo "<button  class='btn badge rounded-pill bg-label-danger me-1'>Fail</button>";
  
            }
        echo "</td>";
        echo "</tr>";
    }
?>
</tbody>
</table>
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
if(isset($_POST['status'])){
    $status = $_POST['status'];
    $user_id = $_POST['uid'];

    if($status==1){
        $status=0;
    } else{
        $status=1;
    }
    $qry = "UPDATE tbl_user SET status=$status WHERE id = $user_id";
    if(mysqli_query($conn,$qry)){
        
    } else {
        echo "<script>alert('Something wrong!');</script>";
    }
    
    
}
?>