<?php

function changeReadStatus($message_id,$con) {
    $sql = "UPDATE tbl_messages SET is_read = 1 WHERE message_id = ?";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i",$message_id);
        $stmt->execute();
        $stmt->close();
    }
}



function getRecentMessagesOrNot($sender_id,$receiver_id,$con) {
    $new_messages = false;
    $sql = "SELECT COUNT(*) AS count FROM tbl_messages WHERE sender_id = ? AND receiver_id = ? AND is_read = 0;";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ii",$sender_id,$receiver_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $response_data = $result->fetch_assoc(); 
       
        if ($response_data["count"] > 0) {
            $new_messages = true;
        }

        unset($response_data);
        $result->free();
        $stmt->close();
    }

    return $new_messages;
}
