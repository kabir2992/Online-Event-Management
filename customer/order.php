<?php include "header.php"; 
@session_start();
include "../database/db.php";

$user = $_SESSION["id"];
?>
<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4">My Bookings</h4>

<!-- Basic Layout -->
<div class="row">

    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header p-0">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                                Completed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">
                                Pending
                            </button>
                        </li>
                        <span class="tab-slider" style="left: 0px; width: 91.1719px; bottom: 0px;"></span></ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content p-0">

                    <!--TAB 1-->
                    <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">

                        <div class="table-responsive text-nowrap">
                            <?php
                            $qry = "
                            SELECT 
                                b.booking_id,
                                b.start_date,
                                b.end_date,
                                b.booking_date,
                                b.booking_status,
                                b.total_amount,
                                v.name AS venue_name,
                                v.address AS venue_address,
                                v.price AS venue_price,
                                s.service_name
                            FROM 
                                tbl_booking b
                            LEFT JOIN 
                                tbl_venue_book vb ON b.booking_id = vb.booking_id
                            LEFT JOIN 
                                tbl_venue v ON vb.venue_id = v.venue_id
                            LEFT JOIN 
                                tbl_service_book sb ON b.booking_id = sb.booking_id
                            LEFT JOIN 
                                tbl_service s ON sb.service_id = s.service_id
                            WHERE 
                                b.user_id = $user;
                        
                        ";
                            $result = mysqli_query($conn, $qry);

                            if (!$result) {
                                // Print the error message and exit
                                die("Query failed: " . mysqli_error($conn));
                            }
                            ?>
                            <table id="catable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <th>Status</th>
                                    <th>Venue</th>
                                    <th>Venue Address</th>
                                    <th>Venue Price</th>
                                    <th>Booking start Date</th>
                                    <th>Booking End Date</th>
                                    <th>Order Date</th>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>";
                                        if ($row['booking_status'] == 1) {
                                            echo "<span class='btn badge rounded-pill bg-label-success me-1'>Completed</span>";
                                        } else {
                                            echo "<span class='btn badge rounded-pill bg-label-warning me-1'>Pending</span>";
                                        }
                                        echo "</td>";
                                        echo "<td>" . $row['total_amount'] . "</td>";
                                        echo "<td>" . $row['venue_address'] . "</td>";
                                        echo "<td>" . $row['venue_price'] . "</td>";
                                        echo "<td>" . $row['start_date'] . "</td>";
                                        echo "<td>" . $row['end_date'] . "</td>";
                                        echo "<td>" . $row['booking_date'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!--END TAB 1-->

                    <!--TAB 2-->
                    <!-- <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                        <div class="table-responsive text-nowrap">
                            <?php
                            $qry = "
                            SELECT tv.name venue,tv.address,booked_date,order_date,
                                CASE WHEN order_status=1 THEN 'Completed' ELSE 'Pending' END status,total_amount,(SELECT contact FROM tbl_user WHERE id=tv.vid) contact   
                            FROM tbl_booking tb, tbl_venue tv, tbl_user tu
                            WHERE 
                                tb.cid=tu.id AND
                                tb.vnid=tv.id AND tb.cid=$user AND tb.order_status=0;";
                            $result = mysqli_query($conn, $qry);

                            if (!$result) {
                                // Print the error message and exit
                                die("Query failed: " . mysqli_error($conn));
                            }
                            ?>
                            <table id="detable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <th>Status</th>
                                    <th>Venue</th>
                                    <th>Venue Address</th>
                                    <th>Booking Date</th>
                                    <th>Order Date</th>
                                </thead>
                                <tbody>
                                    <?php
                                    // while ($row = mysqli_fetch_assoc($result)) {
                                    //     echo "<tr>";
                                    //     echo "<td>";
                                    //     if ($row['status'] == "Completed") {
                                    //         echo "<span class='btn badge rounded-pill bg-label-success me-1'>" . $row['status'] . "</span>";
                                    //     } else {
                                    //         echo "<span class='btn badge rounded-pill bg-label-warning me-1'>" . $row['status'] . "</span>";
                                    //     }
                                    //     echo "</td>";
                                    //     echo "<td>" . $row['venue'] . "</td>";
                                    //     echo "<td>" . $row['address'] . "</td>";
                                    //     echo "<td>" . $row['booked_date'] . "</td>";
                                    //     echo "<td>" . $row['booking_date'] . "</td>";
                                    //     echo "</tr>";
                                    // }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <!--END TAB 2-->

                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
    <script src="assets/DataTables/datatables.min.js"></script>
    <script>
        new DataTable("#catable");
        new DataTable("#detable");
        new DataTable("#djtable");

    </script>
