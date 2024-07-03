<?php
include "header.php";
include "../database/db.php";
@session_start();

$user = $_SESSION["id"];

function fetchData($conn, $query, $fieldName)
{
	$result = mysqli_query($conn, $query);

	if (!$result) {
		die("Query failed: " . mysqli_error($conn));
	}

	$data = mysqli_fetch_assoc($result);

	if (!$data) {
		die("Error fetching $fieldName: No data");
	}

	return $data[$fieldName];
}


$query = "SELECT SUM(total_amount) AS earn FROM tbl_vendor_payment WHERE vendor_id = $user";
$earn = fetchData($conn, $query, "earn");

// $total_customer = fetchData($conn, "
// SELECT COUNT(DISTINCT tb.user_id) AS total_customer
// FROM tbl_booking tb
// LEFT JOIN tbl_venue_book tvb ON tb.booking_id = tvb.booking_id
// LEFT JOIN tbl_service_book tsb ON tb.booking_id = tsb.booking_id
// LEFT JOIN tbl_venue_services tvs ON tvb.venue_id = tvs.venue_id
// LEFT JOIN tbl_venue tv ON tvs.venue_id = tv.venue_id
// WHERE tv.vendor_id = $user", "total_customer");


$total_customer = fetchData($conn, "
SELECT COUNT(DISTINCT tb.user_id) AS total_customer
FROM tbl_booking tb,tbl_venue_book tvb,tbl_service_book tsb,tbl_venue_services tvs,tbl_venue tv
WHERE tb.booking_id = tvb.booking_id AND tb.booking_id = tsb.booking_id AND tv.venue_id = tvb.venue_id AND tv.vendor_id = $user", "total_customer");


$total_bookings = fetchData($conn, "SELECT 
(SELECT COUNT(1) FROM tbl_venue_book tvb INNER JOIN tbl_booking tb ON tvb.booking_id = tb.booking_id INNER JOIN tbl_venue tv ON tvb.venue_id = tv.venue_id WHERE tv.vendor_id = $user) AS total_venue_bookings,
(SELECT COUNT(1) FROM tbl_service_book tsb INNER JOIN tbl_venue_services tvs ON tsb.service_id = tvs.service_id INNER JOIN tbl_venue tv ON tvs.venue_id = tv.venue_id WHERE tv.vendor_id = $user) AS total_service_bookings,
COUNT(DISTINCT tb.user_id) AS total_customers
FROM 
tbl_booking tb
LEFT JOIN 
tbl_venue_book tvb ON tb.booking_id = tvb.booking_id
LEFT JOIN 
tbl_service_book tsb ON tb.booking_id = tsb.booking_id
LEFT JOIN 
tbl_venue_services tvs ON tvb.venue_id = tvs.venue_id
LEFT JOIN 
tbl_venue tv ON tvb.venue_id = tv.venue_id
WHERE 
tv.vendor_id = $user", "total_venue_bookings");

$total_venues = fetchData($conn, "SELECT COUNT(venue_id) AS total_venues FROM tbl_venue WHERE vendor_id = $user AND status = 1", "total_venues");

$r = mysqli_query($conn, "SELECT DATE(date_time) AS x, SUM(vp.total_amount) AS y FROM tbl_vendor_payment vp, tbl_booking tb WHERE vp.booking_id = tb.booking_id AND vp.vendor_id=$user GROUP BY DATE(date_time);");
$jsonArr1 = array();
while ($rw = mysqli_fetch_assoc($r))
	$jsonArr1[] = $rw;
$jsonArr1 = json_encode($jsonArr1);

$r = mysqli_query($conn, "SELECT MONTHNAME(date_time) AS x, SUM(vp.total_amount) AS y FROM tbl_vendor_payment vp, tbl_booking tb WHERE vp.booking_id = tb.booking_id AND vp.vendor_id=$user GROUP BY MONTHNAME(date_time)");
$jsonArr2 = array();
while ($rw = mysqli_fetch_assoc($r))
	$jsonArr2[] = $rw;
$jsonArr2 = json_encode($jsonArr2);

// Fetch upcoming bookings data
// $upcomingBookingsQuery = "SELECT tb.booking_date, tb.start_date, tb.end_date, tv.name AS venue_name, tu.name AS customer_name
//                           FROM tbl_booking tb
//                           LEFT JOIN tbl_venue tv ON tb.vnid = tv.id
//                           LEFT JOIN tbl_venue,book tv ON tb.vnid = tv.id
//                           LEFT JOIN tbl_user tu ON tb.cid = tu.id
//                           WHERE tb.vdid = $user AND tb.start_date > NOW() AND tu.user_type = 'C'
//                           ORDER BY tb.start_date ASC
//                           LIMIT 5";// You can adjust the query as needed
// $upcomingBookingsResult = mysqli_query($conn, $upcomingBookingsQuery);

// if (!$upcomingBookingsResult) {
//   die("Query failed: " . mysqli_error($conn));
// }

?>

<h4 class="py-3 mb-4"> Dashboard </h4>

<div class="row gy-4">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<div class="d-flex align-items-center justify-content-between">
					<h5 class="card-title m-0 me-2">Transactions</h5>
				</div>
			</div>
			<div class="card-body">
				<div class="row g-3">
					<div class="col-md-3 col-6">
						<div class="d-flex align-items-center">
							<div class="avatar">
								<div class="avatar-initial bg-primary rounded shadow">
									<i class="mdi mdi-account-outline mdi-24px"></i>
								</div>
							</div>
							<div class="ms-3">
								<div class="small mb-1">Customers</div>
								<h5 class="mb-0">
									<?php echo $total_customer; ?>
								</h5>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-6">
						<div class="d-flex align-items-center">
							<div class="avatar">
								<div class="avatar-initial bg-success rounded shadow">
									<i class="mdi  mdi-check-all mdi-24px"></i>
								</div>
							</div>
							<div class="ms-3">
								<div class="small mb-1">Venues</div>
								<h5 class="mb-0">
									<?php echo $total_venues; ?>
								</h5>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-6">
						<div class="d-flex align-items-center">
							<div class="avatar">
								<div class="avatar-initial bg-warning rounded shadow">
									<i class="mdi mdi-receipt-clock-outline mdi-24px"></i>
								</div>
							</div>
							<div class="ms-3">
								<div class="small mb-1">Bookings</div>
								<h5 class="mb-0">
									<?php echo $total_bookings; ?>
								</h5>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-6">
						<div class="d-flex align-items-center">
							<div class="avatar">
								<div class="avatar-initial bg-info rounded shadow">
									<i class="mdi mdi-currency-inr mdi-24px"></i>
								</div>
							</div>
							<div class="ms-3">
								<div class="small mb-1">Revenue</div>
								<h5 class="mb-0">
									<?php echo $earn; ?>
								</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-xl-6 col-md-6 mt-3">
		<div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<h5 class="mb-1">Current Month Earning</h5>
				</div>
			</div>
			<div class="card-body" style="position: relative;">
				<canvas id="myChart"></canvas>
			</div>
		</div>
	</div>

	<div class="col-xl-6 col-md-6 mt-3">
		<div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<h5 class="mb-1"> Monthly Earn</h5>
				</div>
			</div>
			<div class="card-body" style="position: relative;">
				<canvas id="myChart1"></canvas>
			</div>
		</div>
	</div>
</div>

<?php include "footer.php"; ?>
<script src="assets/js/chart.js"></script>
<script>
	const ctx = document.getElementById('myChart');

	var jsonfile = {
		"jsonarray": <?php echo $jsonArr1; ?>
	};

	var labels = jsonfile.jsonarray.map(function (e) {
		return e.x;
	});
	var data = jsonfile.jsonarray.map(function (e) {
		return e.y;
	});

	new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels,
			datasets: [{
				label: '# of Earning',
				data: data,
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
	const ctx1 = document.getElementById('myChart1');

	jsonfile = {
		"jsonarray": <?php echo $jsonArr2; ?>
	};

	labels = jsonfile.jsonarray.map(function (e) {
		return e.x;
	});
	data = jsonfile.jsonarray.map(function (e) {
		return e.y;
	});
	new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: labels,
			datasets: [{
				label: '# of Earning',
				data: data,
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
</script>