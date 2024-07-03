<?php 

require_once '../database/db.php';
require_once '../functions.php';

// get user details
function getUserDetails($conn,$user_id) {
    $data_array = array();
    $sql = "SELECT * FROM tbl_user WHERE user_id = ? LIMIT 1;";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_array = $result->fetch_assoc();

        $result->free();
        $stmt->close();
    }

    return $data_array;
}


date_default_timezone_set("Asia/Kolkata");
function formatDateTime($datetime) {
    $dt = new DateTime($datetime);
    $now = new DateTime();
    
    $date = $dt->format('Y-m-d');
    $time = $dt->format('h:i A');
    
    // Check if the date is today
    if ($date === $now->format('Y-m-d')) {
        return $time . ', Today';
    }
    
    $yesterday = clone $now;
    $yesterday->modify('-1 day');
    
    if ($date === $yesterday->format('Y-m-d')) {
        return $time . ', Yesterday';
    }
    
    return $time . ', ' . $dt->format('M d, Y');
}



// send messages
if (isset($_POST["senderId"]) && isset($_POST["receiverId"]) && isset($_POST["messageText"])) {

    $sender_id = trim($_POST["senderId"]);
    $receiver_id = trim($_POST["receiverId"]);
    $message = trim($_POST["messageText"]);

    $sql = "INSERT INTO tbl_messages(sender_id,receiver_id,message_text) VALUES(?,?,?);";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis",$sender_id,$receiver_id,$message);
        $stmt->execute();
        $stmt->close();
    }

    $message_time = date("Y-m-d H:i:s");

    echo '<div class="chat-message-right mb-4">
            <div class="mx-2">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                    class="rounded-circle mr-1" alt="Chris Wood" width="30" height="30">
            </div>
            <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                '.htmlspecialchars_decode($message).'
                <div class="text-muted small text-nowrap mt-2 message-time">'.formatDateTime($message_time).'</div>
            </div>
        </div>';
}




// fetch message chat
if (isset($_POST["senderId"]) && isset($_POST["receiverId"]) && isset($_POST["Number"]) && isset($_POST["showChat"])) {

    $sender_id = trim($_POST["senderId"]);
    $receiver_id = trim($_POST["receiverId"]);

    $sql = "SELECT * FROM tbl_messages WHERE (sender_id = ? OR sender_id = ?) AND (receiver_id = ? OR receiver_id = ?);";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiii",$sender_id,$receiver_id,$sender_id,$receiver_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $user_data = array();
        $user_data = getUserDetails($conn,$receiver_id);

        echo '<div class="py-2 px-4 border-bottom d-none d-lg-block">
            <div class="d-flex align-items-center py-1">
                <div class="position-relative">
                    <img src="assets/img/avatars/1.png"
                        class="rounded-circle mr-1" alt="Profile Picture" width="40" height="40">
                </div>
                <div class="flex-grow-1 pl-3 mx-2">
                    <strong>'.$user_data["name"].'</strong>
                </div>
            </div>
        </div>

        <div class="position-relative">
            <div class="chat-messages p-4 users-chat-history-container">';

        while ($message_data = $result->fetch_assoc()) {

            if (($message_data["is_read"] == 0) && ($message_data["sender_id"] != $sender_id)) {
                changeReadStatus($message_data["message_id"],$conn);
            }

            // checking for id
            if ($message_data["sender_id"] == $sender_id) {
                echo '<div class="chat-message-right mb-4">
                    <div class="mx-2">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                            class="rounded-circle mr-1" alt="Chris Wood" width="30" height="30">
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                        '.htmlspecialchars_decode($message_data["message_text"]).'
                        <div class="text-muted small text-nowrap mt-2 message-time">'.formatDateTime($message_data["sent_time"]).'</div>
                    </div>
                </div>';
            } else {
                echo '<div class="chat-message-left pb-4">
                    <div class="mx-2">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png"
                            class="rounded-circle mr-1" alt="Sharon Lessman" width="30" height="30">
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                        '.htmlspecialchars_decode($message_data["message_text"]).'
                        <div class="text-muted small text-nowrap mt-2 message-time">'.formatDateTime($message_data["sent_time"]).'</div>
                    </div>
                </div>';
            }
        }

        echo '</div>
        </div>

        <div class="flex-grow-0 py-3 px-4 border-top">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Type your message" id="message-textbox">
                <button class="btn btn-primary" id="send-message-btn" onclick="sendTextMessage('.$sender_id.','.$receiver_id.')">Send</button>
            </div>
        </div>';

        // function to reload chat after every 5 seconds
        echo '<script> fetchNewMessages('.$sender_id.','.$receiver_id.','.$_POST["Number"].'); </script>';

        $stmt->close();
    }
}



// fetch message chat
if (isset($_POST["senderId"]) && isset($_POST["receiverId"]) && isset($_POST["Number"]) && isset($_POST["updateChats"])) {

    $sender_id = trim($_POST["senderId"]);
    $receiver_id = trim($_POST["receiverId"]);

    $sql = "SELECT * FROM tbl_messages WHERE (sender_id = ? OR sender_id = ?) AND (receiver_id = ? OR receiver_id = ?);";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiii",$sender_id,$receiver_id,$sender_id,$receiver_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $user_data = array();
        $user_data = getUserDetails($conn,$receiver_id);

        while ($message_data = $result->fetch_assoc()) {

            if (($message_data["is_read"] == 0) && ($message_data["sender_id"] != $sender_id)) {
                changeReadStatus($message_data["message_id"],$conn);
            }

            // checking for id
            if ($message_data["sender_id"] == $sender_id) {
                echo '<div class="chat-message-right mb-4">
                    <div class="mx-2">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                            class="rounded-circle mr-1" alt="Chris Wood" width="30" height="30">
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                        '.htmlspecialchars_decode($message_data["message_text"]).'
                        <div class="text-muted small text-nowrap mt-2 message-time">'.formatDateTime($message_data["sent_time"]).'</div>
                    </div>
                </div>';
            } else {
                echo '<div class="chat-message-left pb-4">
                    <div class="mx-2">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png"
                            class="rounded-circle mr-1" alt="Sharon Lessman" width="30" height="30">
                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                        '.htmlspecialchars_decode($message_data["message_text"]).'
                        <div class="text-muted small text-nowrap mt-2 message-time">'.formatDateTime($message_data["sent_time"]).'</div>
                    </div>
                </div>';
            }
        }

        // function to reload chat after every 5 seconds
        echo '<script> fetchNewMessages('.$sender_id.','.$receiver_id.','.$_POST["Number"].'); </script>';

        $stmt->close();
    }
}