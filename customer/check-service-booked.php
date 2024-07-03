<?php 

if (isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["fromTime"]) && isset($_POST["toTime"]) && isset($_POST["serviceId"])) {

    session_start();
    include "../database/db.php";

    $startDate = trim(($_POST["startDate"]));
    $endDate = trim(($_POST["endDate"]));
    $fromTime = trim(($_POST["fromTime"]));
    $toTime = trim(($_POST["toTime"]));
    $serviceId = trim(($_POST["serviceId"]));
    $dataCount = 0;

    $sql = "SELECT COUNT(*) AS count 
        FROM tbl_booking tb
        JOIN tbl_service_book ts ON tb.booking_id = ts.booking_id
        WHERE ts.service_id = ?
        AND tb.start_date <= ?
        AND tb.end_date >= ?
        AND tb.from_time <= ?
        AND tb.to_time >= ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issss", $serviceId, $startDate, $endDate, $fromTime, $toTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row["count"] > 0) {
            $dataCount = 1;
        }
        $result->free();
        $stmt->close();
    }

    echo $dataCount;
}

?>