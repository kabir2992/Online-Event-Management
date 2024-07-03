<?php
include "../database/db.php";

$r = mysqli_query($conn,"SELECT email,name FROM tbl_user");
$arr = array();
while($rw  = mysqli_fetch_assoc($r))
    $arr[]=$rw;
echo json_encode($arr);
