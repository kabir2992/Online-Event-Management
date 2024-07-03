<?php
include "header.php";
include "../database/db.php";
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();
$vendorID = $_SESSION['id'];
$error_message = '';


if (isset($_POST['btn_venue'])) {

    function renameFileIfExists($folderPath, $fileName) {
        $newFileName = $fileName;
        $counter = 1;
    
        while (file_exists($folderPath . '/' . $newFileName)) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . $counter . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $counter++;
        }
    
        return $newFileName;
    }


    // echo '<pre>';
    // print_r($_POST);
    // print_r($_FILES);
    // echo '</pre>';

    $venue_id = htmlspecialchars(trim($_POST["venueId"]));

    // Capture the vendor's ID
    $vendorID = $_SESSION['id'];

    // File upload and validation
    $uploadDir = "../images/venue_img/";
    $validExtensions = array('png', 'jpg', 'jpeg');

    $check = true;
    $error_message = '';


    if (!empty($_FILES['images']['name'][0])) {
        // Validate file uploads
        $allowedFileTypes = array('png', 'jpg', 'jpeg');
        $maxFileSize = 8 * 1024 * 1024; // 2MB

        $uploadedFiles = $_FILES['images'];

        foreach ($uploadedFiles['name'] as $key => $fileName) {
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileSize = $uploadedFiles['size'][$key];

            if (in_array(strtolower($fileType), $allowedFileTypes) && $fileSize <= $maxFileSize) {
                
            } else {
                $error_message = "Error: Invalid file type or size. Allowed types: PNG, JPG, JPEG.";
                $check = false;
                break;
            }
        }

    }
    
    
    if ($check == true) {

        $uploadDirectory = "../images/venue_img/";

        // loop to store files
        $uploadedFiles = $_FILES["images"];

        $fileCount = count($_FILES['images']['name']);

        if ($fileCount > 0) {
            foreach ($uploadedFiles['name'] as $key => $fileName) {
                if (!empty($fileName)) {
                    // check for same file in directory...
                    $newFileName = renameFileIfExists($uploadDirectory,$fileName);
            
                    // Upload file to server
                    $uploadedFilePath = $uploadDirectory . basename($newFileName);
                    if (move_uploaded_file($uploadedFiles['tmp_name'][$key], $uploadedFilePath));
        
                    // store into database...
                    $sql = "INSERT INTO tbl_venue_image(venue_id,image_name) VALUES(?,?);";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("is",$venue_id,$newFileName);
                        $stmt->execute();
                        $stmt->close();
                    }

                }    
            }
        }    
    }


}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Venue</title>
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css">
    <style>
        #vtable img {
            width: 80px; /* Increase the image width */
            height: 80px; /* Increase the image height */
        }

        .services-table {
            border-collapse: collapse;
            width: 68%;
        }

        .services-table th,
        .services-table td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        .services-table label {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <h4 class="py-3 mb-4">Update Venue</h4>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateForm();">
                        <p class="text-danger">
                                <?php echo $error_message; ?>
                            </p>
                        <div class="form-floating form-floating-outline mb-4">
                            <select name='venueId' class="form-select" id="venue-name">
                                <option value="default">Select Venue</option>
                                <?php 
                                
                                $sql = "SELECT venue_id,name FROM tbl_venue WHERE vendor_id = ?";
                                if ($stmt= $conn->prepare($sql)) {
                                    $stmt->bind_param("i",$vendorID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($venue_data = $result->fetch_assoc()) {
                                        echo '<option value="'.$venue_data["venue_id"].'">'.$venue_data["name"].'</option>';
                                    }

                                    $stmt->close();
                                }
                                
                                ?>
                            </select>
                            <label for="basic-default-fullname">Select Venue</label>
                            <p class="text-danger" id="error-message"></p>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image</label>
                            <input class="form-control" name='images[]' type="file" id="formFile" multiple/>
                        </div>

                        <button type="submit" name='btn_venue' class="btn btn-primary" value="saveBtn">Save</button>
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
        $(document).ready(function () {
            $('#vtable').DataTable();
        });

        function validateForm() {
            var value = $("#venue-name").val();

            if (value == "default") {
                $("#error-message").html("Select venue name.");
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>
</html>
