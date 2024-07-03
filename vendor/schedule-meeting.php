<?php 

if (isset($_POST["requestId"]) && isset($_POST["meeting"])) {

    $requestId = trim($_POST["requestId"]);
    $meeting_time = trim($_POST["meetingTime"]);
    $meeting_date = trim($_POST["meetingDate"]);

    function generateRandomCode($length = 20) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
    
        // Generate random code
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
    
        return $code;
    }

    $code = '';
    $code = generateRandomCode();

    include "../database/db.php";

    $sql = "INSERT INTO tbl_scheduled_meetings(request_id,meeting_date,meeting_time,room_code) VALUES(?,?,?,?);";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isss",$requestId,$meeting_date,$meeting_time,$code);
        if ($stmt->execute()) {
            echo 'Inserted';
        }
    }

    $conn->close();
}
?>