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
       
    </style>
</head>

<body>
    <div class="container">
        <?php
        include "../database/db.php";
        include "header.php";

        $request_id = 0;

        if (isset($_GET["id"])) {
            $request_id = trim($_GET["id"]);
        }

        if ($request_id <= 0) {
            echo 'Something went wrong...';
            die;
        }

        // function to update meeting status
        function updateMeetingStatus($con,$request_id) {
            $sql = "UPDATE tbl_scheduled_meetings SET user_status = 0 WHERE request_id = ?";
        
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param("i",$request_id);
                $stmt->execute();      
                $stmt->close();
            }
        }

        updateMeetingStatus($conn,$request_id);

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

        $meeting_data = getScheduledMeetingData($conn,$request_id);
        $room_code = '';

        if (!empty($meeting_data)) {
            $room_code = $meeting_data['room_code'];
        }
        ?>

        <div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="meet"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            const domain = 'meet.jit.si';
            const options = {
                roomName: '<?php echo $room_code; ?>',
                width: '75vw',
                height: 600,
                parentNode: document.getElementById('meet'),
                configOverwrite: {
                    prejoinPageEnabled: false
                },
                interfaceConfigOverwrite: {
                    SHOW_WATERMARK_FOR_GUESTS: false
                },
                userInfo: {
                    displayName: '<?php echo ucfirst(strtolower($_SESSION["name"])); ?>'
                },
                onload: () => {
                    // Handle screen sharing button click event
                    $('#start-screen-sharing').click(() => {
                        api.executeCommand('toggleShareScreen');
                    });
                }
            };

            const api = new JitsiMeetExternalAPI(domain, options);
        });
    </script>
</body>

</html>