<!DOCTYPE html>
<html lang="en">
<head>
    <title>Meetings</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/image-gallery.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <script src="https://meet.jit.si/external_api.js"></script>
    <style>
       .table-responsive::-webkit-scrollbar {
			display: none;
		}

		/* Hide scrollbar for IE, Edge and Firefox */
		.table-responsive {
			-ms-overflow-style: none;  /* IE and Edge */
			scrollbar-width: none;  /* Firefox */
		}
    </style>
</head>

<body>
    <div class="container">
        <?php
        include "../database/db.php";
        include "header.php";
        ?>

        <div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <h2>Meeting</h2>
                            <div class="table-responsive">
                                <table id="meetingTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Agenda</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        date_default_timezone_set("Asia/Kolkata");

                                        function checkMeetingStatus($con,$request_id) {
                                            $sql = "SELECT user_status FROM tbl_scheduled_meetings WHERE request_id = ?;";
                                            $check = false;

                                            if ($stmt = $con->prepare($sql)) {
                                                $stmt->bind_param("i",$request_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    if ($row["user_status"] == 0) {
                                                        $check = true;
                                                    }
                                                }

                                                $result->free();
                                                $stmt->close();
                                            }

                                            return $check;
                                        }

                                        function checkScheduledMeeting($con,$request_id) {
                                            $sql = "SELECT request_id FROM tbl_scheduled_meetings WHERE request_id = ?;";
                                            $check = false;

                                            if ($stmt = $con->prepare($sql)) {
                                                $stmt->bind_param("i",$request_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $check = true;
                                                }

                                                $result->free();
                                                $stmt->close();
                                            }

                                            return $check;
                                        }


                                        function getScheduledMeetingData($con,$request_id) {
                                            $sql = "SELECT * FROM tbl_scheduled_meetings WHERE request_id = ?;";
                                            $data_array = array();

                                            if ($stmt = $con->prepare($sql)) {
                                                $stmt->bind_param("i",$request_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $data_array = $result->fetch_assoc();
                                                }

                                                $result->free();
                                                $stmt->close();
                                            }

                                            return $data_array;
                                        }



                                        $requestQuery = "SELECT mr.*, vi.image_path 
                                            FROM tbl_meeting_requests mr
                                            JOIN tbl_venue v ON mr.venue_id = v.venue_id
                                            LEFT JOIN (
                                                SELECT venue_id, MIN(image_name) AS image_path
                                                FROM tbl_venue_image
                                                GROUP BY venue_id
                                            ) vi ON v.venue_id = vi.venue_id
                                            WHERE mr.user_id = " . $_SESSION['id'] . ";";
                                        $requestResult = mysqli_query($conn, $requestQuery);

                                        // Display meeting requests in a table
                                        while ($row = mysqli_fetch_assoc($requestResult)) {

                                            // echo '<pre>';
                                            // print_r($row);
                                            // echo '</pre>';

                                            $timestamp = strtotime($row['meeting_date']);
                                            $formattedDate = date('d F, Y', $timestamp);

                                            echo "<tr>";
                                            echo "<td>{$formattedDate}</td>";
                                            echo "<td>{$row['meeting_time']}</td>";
                                            echo "<td>{$row['meeting_agenda']}</td>";
                                            echo "<td><img src='http://localhost/ems/images/venue_img/{$row['image_path']}' width='80' height='80'></td>";

                                            echo "<td>";

                                            echo "<input type='hidden' name='request_id' value='{$row['request_id']}'>";
                                            if ($row['status'] == 1) {
                                                echo "<button id='btn_status' name='status' value='' class='btn badge rounded-pill bg-label-primary me-1 mt-3'>Accepted</button>";
                                            } else {
                                                echo "<button id='btn_status' name='status' value='' class='btn badge rounded-pill bg-label-primary me-1 mt-3'>Pending</button>";
                                            }

                                            echo "</td>";
                                            echo "<td>";
                                            if ($row['status'] == 1) {

                                                if (checkScheduledMeeting($conn,$row["request_id"]) == true) {
                                                    $meeting_data = getScheduledMeetingData($conn, $row["request_id"]);
                                                    $meeting_date = trim($meeting_data["meeting_date"]);
                                                    $meeting_time = trim($meeting_data["meeting_time"]);

                                                    // Create DateTime object for meeting date and time
                                                    $meeting_datetime = new DateTime($meeting_date . ' ' . $meeting_time);
                                                    $current_datetime = new DateTime();

                                                    if ($current_datetime >= $meeting_datetime) {

                                                        // checking whether meeting is done or not

                                                        if (checkMeetingStatus($conn,$row["request_id"])) {
                                                            echo "<button id='' class='btn badge rounded-pill bg-label-danger me-1 mt-3'>Ended</button>";
                                                        } else {
                                                            echo '<button type="button" class="btn btn-primary btn-icon m-3" onclick="startMeetingButton('.$row["request_id"].')">
                                                                <span class="mdi mdi-video-plus-outline"></span>
                                                            </button>';
                                                        }
                                                    } else {
                                                        $formatted_date_time = $meeting_datetime->format('j F, Y h:i A');
                                                        echo '<p class="">Scheduled on ' . $formatted_date_time . '</p>';
                                                    }

                                                }
                                            }
                                            echo "</td>";

                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#meetingTable').DataTable();
        });

        function startMeetingButton(id) {
            window.location.href = "join-meeting.php?id="+id;
        }
    </script>
</body>

</html>