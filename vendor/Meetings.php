<?php
include("header.php");
include "../database/db.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css">
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
</head>

<body>
    <div class="container mt-5">

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
                                        $sql = "SELECT vendor_status FROM tbl_scheduled_meetings WHERE request_id = ?;";
                                        $check = false;

                                        if ($stmt = $con->prepare($sql)) {
                                            $stmt->bind_param("i",$request_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                if ($row["vendor_status"] == 0) {
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
                                        WHERE v.vendor_id = " . $_SESSION['id'] . ";";
                                    $requestResult = mysqli_query($conn, $requestQuery);

                                    // Display meeting requests in a table
                                    while ($row = mysqli_fetch_assoc($requestResult)) {

                                        // echo '<pre>';
                                        // print_r($row);
                                        // echo '</pre>';

                                        echo "<tr>";
                                        echo "<td>{$row['meeting_date']}</td>";
                                        echo "<td>{$row['meeting_time']}</td>";
                                        echo "<td>{$row['meeting_agenda']}</td>";
                                        echo "<td><img src='http://localhost/ems/images/venue_img/{$row['image_path']}' width='80' height='80'></td>";

                                        echo "<td>";

                                        echo "<form method='post'>";
                                        echo "<input type='hidden' name='request_id' value='{$row['request_id']}'>";
                                        if ($row['status'] == 1) {
                                            echo "<button id='btn_status'  type='submit' name='status' value='" . $row['status'] . "' class='btn badge rounded-pill bg-label-primary me-1 mt-3'>Accepted</button>";
                                        } else {
                                            echo "<button id='btn_status' type='submit' name='status' value='" . $row['status'] . "' class='btn badge rounded-pill bg-label-primary me-1 mt-3'>Pending</button>";
                                        }
                                        echo "</form>";
                                        echo "</td>";
                                        echo "<td>";
                                        if ($row['status'] == 1) {

                                            if (checkScheduledMeeting($conn,$row["request_id"]) == false) {
                                                // If the request is accepted, show the button to create a meeting
                                                echo '<button class="btn btn-primary d-grid w-100 mt-2" id="requestMeetLink" data-toggle="modal" data-target="#meetingRequestModal">Create Meeting </button>';
                                                echo '<!--Modal!-->'; 
                                                echo '<div class="modal fade" id="meetingRequestModal" tabindex="-1" role="dialog" aria-labelledby="meetingRequestModalLabel" aria-hidden="true">';
                                                echo '    <div class="modal-dialog" role="document">';
                                                echo '        <div class="modal-content">';
                                                echo '            <div class="modal-header">';
                                                echo '                <h5 class="modal-title" id="meetingRequestModalLabel">Schedule a Virtual Meeting</h5>';
                                                echo '                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-model-btn">';
                                                echo '                    <span aria-hidden="true">&times;</span>';
                                                echo '                </button>';
                                                echo '            </div>';
                                                echo '            <div class="modal-body">';
                                                echo '                    <div class="form-group">';
                                                echo '                        <label for="meetingDate">Meeting Date:</label>';
                                                echo '                        <input type="date" class="form-control" id="meetingDate" name="meetingDate" required>';
                                                echo '                    </div>';
                                                echo '                    <div class="form-group">';
                                                echo '                        <label for="meetingTime">Meeting Time:</label>';
                                                echo '                        <input type="time" class="form-control" id="meetingTime" name="meetingTime" required>';
                                                echo '                    </div>';
                                                echo '                    <br>';
                                                echo '                    <button type="button" class="btn btn-primary d-grid w-100" id="schedule-meeting-data" name="scheduleMeetingForm" onclick="scheduleMeetingForm('.$row["request_id"].');">Submit</button>';
                                                echo '            </div>';
                                                echo '        </div>';
                                                echo '    </div>';
                                                echo '</div>';
                                            } else {
                                                $meeting_data = getScheduledMeetingData($conn, $row["request_id"]);
                                                $meeting_date = trim($meeting_data["meeting_date"]);
                                                $meeting_time = trim($meeting_data["meeting_time"]);

                                                // Create DateTime object for meeting date and time
                                                $meeting_datetime = new DateTime($meeting_date . ' ' . $meeting_time);
                                                $current_datetime = new DateTime();

                                                if ($current_datetime >= $meeting_datetime) {
                                                    if (checkMeetingStatus($conn,$row["request_id"])) {
                                                        echo "<button id='' class='btn badge rounded-pill bg-label-danger me-1 mt-3'>Ended</button>";
                                                    } else {
                                                        echo '<button type="button" class="btn btn-primary btn-icon m-3" onclick="startMeetingButton('.$row["request_id"].')">
                                                            <span class="mdi mdi-video-vintage"></span>
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


        // schedule meeting function
        function scheduleMeetingForm(rid) {
            //alert(rid);
            var meeting_date = $("#meetingDate").val().trim();
            var meeting_time = $("#meetingTime").val().trim();

            if (meeting_date == "" || meeting_time == "") {
                return;
            }

            $.ajax({
                url: 'schedule-meeting.php',
                type: 'POST',
                data: {
                    requestId: rid,
                    meetingDate: meeting_date,
                    meetingTime: meeting_time,
                    meeting: "Meeting"
                },
                beforeSend: function() {
                    $('#close-model-btn').click();
                },
                success: function (response) {
                    if (response == "Inserted") {
                        alert("Meeting scheduled successfully.");
                    } else {
                        alert("Unable to schedule meetinig.");
                    }
                }
            });
        }
       
        
        function startMeetingButton(id) {
            window.location.href = "start-meeting.php?id="+id;
        }
    </script>
    <?php
    if (isset($_POST['status'])) {
        $status = $_POST['status'];
        $requestId = $_POST['request_id'];
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $qry = "UPDATE tbl_meeting_requests SET status=$status WHERE request_id=$requestId";
        if (mysqli_query($conn, $qry)) {
        } else {
            echo "<script>alert('Something wrong!');</script>";
        }
    }
    ?>
</body>

</html>