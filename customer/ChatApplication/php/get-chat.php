<?php
error_reporting(0);
    $session_start = session_start();
    if(isset($_SESSION['id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        
        date_default_timezone_set('Asia/Kolkata');
        
        $sql = "SELECT * FROM messages LEFT JOIN tbl_user ON tbl_user.user_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $msgDate = date("Y-m-d", strtotime($row['msg_date']));
            $today = date("Y-m-d");
            $yesterday = date("Y-m-d", strtotime('yesterday'));

            if (date("Y-m-d", strtotime($msgDate)) === $today) 
            {
                $dateDisplay = "Today";
            } 
            elseif (date("Y-m-d", strtotime($msgDate)) === $yesterday) 
            {
                $dateDisplay = "Yesterday";
            } 
            else 
            {
                $dateDisplay = date("M j, Y", strtotime($msgDate));
            }


            if ($dateDisplay != $prevDate) {
                $output .= '<div >
                            <p style="font-size:18px; text-align: center;">' . $dateDisplay . '</p>
                        </div>';

                $prevDate = $dateDisplay;
            }

            $msgTime = date("H:i", strtotime($row['time']));
            
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p style="text-align: justify;">' . $row['msg'] . ' &nbsp;&nbsp; <span style="font-size: 10px;">' . $msgTime . '</span></p>
                            </div>
                        </div>';
            } else {
                $output .= '<div class="chat incoming">
                            
                            <div class="details">
                                <p style="text-align: justify;">' . $row['msg'] . ' &nbsp;&nbsp; <span style="font-size: 10px;"> ' . $msgTime . '</span></p>
                            </div>
                        </div>';
            }

            // Update $prevDate after each message is processed
            $prevDate = $dateDisplay;
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    echo $output;
}else{
        header("location: ../login.php");
    }

//echo "Today: $today<br>";
//echo "Yesterday: $yesterday<br>";
//echo "Message Date: $msgDate<br>";

