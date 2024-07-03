<?php 
    session_start();
    include_once "config.php";
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($email) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            
            $enc_pass = $row['password'];
            if(password_verify($password, $enc_pass)){
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE tbl_user SET chat_status = '{$status}' WHERE user_id = {$row['user_id']}");
                if($sql2){
                    $_SESSION['id'] = $row['user_id'];
                    echo "success";
                }else{
                    echo "Something went wrong. Please try again!";
                }
            }else{
                echo "Email or Password is Incorrect!";
            }
        }else{
            echo "$email - This email not Exist!";
        }
    }else{
        echo "All input fields are required!";
    }
?>