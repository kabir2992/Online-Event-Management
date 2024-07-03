<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['id'];
    $sql = "SELECT * FROM tbl_user WHERE NOT user_id = {$outgoing_id} AND user_type = 'V' ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No Vendors are Available to Chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>