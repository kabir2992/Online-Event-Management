<?php
include("header.php");
include "../database/db.php";
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
    $sql = "UPDATE tbl_scheduled_meetings SET vendor_status = 0 WHERE request_id = ?";
  
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
<!DOCTYPE html>
<html>

<head>
    <title>Conference Meeting</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css">
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <script src="https://meet.jit.si/external_api.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div id="meet"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                    displayName: '<?php echo $_SESSION["name"]; ?>'
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