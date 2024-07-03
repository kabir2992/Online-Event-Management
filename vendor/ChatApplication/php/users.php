<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['id'];
    $sql = "SELECT u.*
    FROM tbl_user u
    JOIN messages m ON u.user_id = m.outgoing_msg_id
    WHERE m.incoming_msg_id = {$outgoing_id}
    AND NOT u.user_type = 'V'
    ORDER BY u.user_id DESC;        
";
    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        // var_dump($outgoing_id);
        $output .= "No Customers are Available to Chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>