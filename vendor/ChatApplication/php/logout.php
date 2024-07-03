<?php
    session_start();
    if(isset($_SESSION['id'])){
        include_once "config.php";
        $logout_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        if(isset($logout_id)){
            $status = "Offline now";
            $sql = mysqli_query($conn, "UPDATE tbl_user SET chat_status = '{$status}' WHERE user_id={$_SESSION['id']}");
            if($sql){
                session_unset();
                session_destroy();
                header("location: ../login.php");
            }
        }else{
            header("location: ../users.php");
        }
    }else{  
        header("location: ../login.php");
    }
?>