<?php

@session_start();
if($_SESSION["role"]!='C'){
  header('Location: /ems/login.php');
}
include "../database/db.php";
$user = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'), true);
if(isset($data["message"])){
    $message = strtolower($data["message"]);
    // filter text message
    $message = mysqli_real_escape_string($conn,$message);
    // match message
    $qry = "SELECT replies FROM chatbot WHERE lower(queries) LIKE '%$message%';";
    $result = mysqli_query($conn,$qry);

    if(mysqli_num_rows($result)>0) {
        $row  = mysqli_fetch_assoc($result);

        // custom query
        if($row["replies"]=='query_order'){
            $qry =  "select concat('Your completed orders: ',a.completed,'and panding orders: ',b.pending) replies from 
            (select count(1) completed from tabl_booking where cid=$user and order_status=1) a,
            (select count(1) pending from tbl_booking where cid=$user and order_status=0) b";
            $result = mysqli_query($conn,$qry);
            $row  = mysqli_fetch_assoc($result);
            $response = array(
                array("text"=>$row["replies"])
            );
        } else {
        // normal respones
            $response = array(
                array("text"=>$row["replies"])
            );
        }
        
    } else {
        $response = array(
            array("text"=>"I didn't get you")
       );
    }
    
    echo json_encode($response);
}

/**

CREATE TABLE chatbot (
    id int primary key auto_increment,
    queries varchar(250),
    replies varchar(250)
);

INSERT INTO chatbot VALUES 
(default,'Hi,Hello,hiii,hola,Hello there,hey,hiiii','Hi! I am beeventful bot');
INSERT INTO chatbot VALUES 
(default,'Good morning,morning','Good morning'),
(default,'Good Night,night','Good night'),
(default,'Good evening,evening','Good evening'),
(default,'Good afternoon,noon,afternoon','Good afternoon'),
(default,'pending,my order,order','query_order');
INSERT INTO chatbot VALUES 
(default,'Bye,Good bye,see you','Ok! see you later');

 select concat('Your completed order: ',a.completed,'and panding orders: ',b.pending) replies from 
 (select count(1) completed from tbl_booking where cid=4 and order_status=1) a,
 (select count(1) pending from tbl_booking where cid=4 and order_status=0) b

INSERT INTO chatbot VALUES 
(default,'how are you,what\'s up','I\'m good! and you?');
INSERT INTO chatbot VALUES 
(default,'I am fine, i\'m fine','Hmm, how can i help you?');

 */


    

