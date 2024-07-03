<?php
include "header.php";
include "../database/db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM tbl_venue WHERE venue_id=$id");
    $row  = mysqli_fetch_assoc($result);
}
?>

<link rel="stylesheet" href="assets/DataTables/datatables.min.css">
<h4 class="py-3 mb-4">Update venue </h4>

<!-- Basic Layout -->
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" name='name' class="form-control" id="basic-default-fullname" value='<?php echo $row["name"]; ?>' placeholder="" />
                        <label for="basic-default-fullname">Name</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-100" name='address' id="exampleFormControlTextarea1"><?php echo $row["address"]; ?></textarea>
                        <label for="exampleFormControlTextarea1">Address</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" name='price' class="form-control" id="basic-default-company" value='<?php echo $row["price"]; ?>' placeholder="" />
                        <label for="basic-default-company">Price</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" name='capacity' class="form-control" id="basic-default-company" value='<?php echo $row["capacity"]; ?>' placeholder="" />
                        <label for="basic-default-company">Capacity</label>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Image</label>
                        <input class="form-control" name='FILE' type="file" id="formFile">
                    </div>

                    <button type="submit" name='btn_venue' class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
<script src="assets/DataTables/datatables.min.js"></script>
<script>
    new DataTable("#vtable");
</script>

<?php

if (isset($_POST['btn_venue'])) {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $file = $_FILES['FILE']['name'];

    if ($file != "") {
        $path = "../images/"; // Set the image path based on the venue path

        if ($_FILES['FILE']['size'] > 0 && ($_FILES['FILE']['type'] == 'image/jpeg' || $_FILES['FILE']['type'] == 'image/png')) {
            if (move_uploaded_file($_FILES['FILE']['tmp_name'], "$path" . $_FILES['FILE']['name'])) {
                $path = "../images/" . $file;
                $qry = "
                    UPDATE tbl_venue SET 
                    name='$name',
                    address='$address',
                    price=$price,
                    capacity=$capacity,
                    image_path='$path'
                    WHERE 
                    id=$id
                ";

                if (mysqli_query($conn, $qry)) {
                    echo "<script>window.location.href='/ems/admin/venue.php';</script>";
                } else {
                    echo "<script>alert('Invalid file data!');</script>";
                }
            } else {
                echo "<script>alert('File upload failed!');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type or size!');</script>";
        }
    } else {
        $qry = "
            UPDATE tbl_venue SET 
            name='$name',
            address='$address',
            price=$price,
            capacity=$capacity
            WHERE 
            venue_id=$id
        ";

        if (mysqli_query($conn, $qry)) {
            echo "<script>window.location.href='/ems/vendor/venue.php';</script>";
        } else {
            echo "<script>alert('Invalid data!');</script>";
        }
    }
}
?>
