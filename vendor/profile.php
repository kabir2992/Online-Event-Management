<?php
include "header.php";
include "../database/db.php";
@session_start();

$qry = "SELECT distinct 
                u.user_id uid,
                u.name uname,
                u.email email,
                u.contact contact,
                u.password upassword,
                u.status ustatus
            FROM tbl_user u
            WHERE u.user_type='V' AND u.user_id=".$_SESSION['id'].";";

$result = mysqli_query($conn, $qry);
$row = mysqli_fetch_assoc($result);
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Profile</h5>
    </div>
    <div class="card-body">
        <form id="formAuthentication" class="mb-3" action="" method="POST">
            <div class="form-floating form-floating-outline mb-3">
                <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="name"
                    value="<?php echo $row['uname']; ?>"
                    placeholder="Enter your name"
                    autofocus />
                <label for="username">Name</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" placeholder="Enter your email" />
                <label for="email">Email</label>
            </div>
            <div class="form-floating form-floating-outline mb-4">
                <input class="form-control" type="tel" name='contact' value="<?php echo $row['contact']; ?>" placeholder="" id="html5-tel-input">
                <label for="html5-tel-input">Phone</label>
            </div>
            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input
                            type="password"
                            id="old_password"
                            class="form-control"
                            name="old_password"
                            placeholder="Enter old password"
                            aria-describedby="old_password" />
                        <label for="old_password">Old Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer" id="toggleOldPassword"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
            </div>
            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input
                            type="password"
                            id="new_password"
                            class="form-control"
                            name="new_password"
                            placeholder="Enter new password"
                            aria-describedby="new_password" />
                        <label for="new_password">New Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer" id="toggleNewPassword"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
            </div>
          <!--  <input type="hidden" name="uid" value="<?php echo $row['user_id']; ?>"> !-->
            <button class="btn btn-primary d-grid" type="submit" name="btn_vendor">Update</button>
        </form>
    </div>
</div>

<?php
include "footer.php";
?>

<script>
    document.getElementById('old_password').style.display = 'none';

    document.getElementById('toggleOldPassword').addEventListener('click', function () {
        var oldPasswordInput = document.getElementById('old_password');
        var oldPasswordToggle = document.getElementById('toggleOldPassword');
        oldPasswordInput.type = (oldPasswordInput.type === 'password') ? 'text' : 'password';
        oldPasswordToggle.innerHTML = (oldPasswordInput.type === 'password') ? '<i class="mdi mdi-eye-off-outline"></i>' : '<i class="mdi mdi-eye-outline"></i>';
    });

    document.getElementById('toggleNewPassword').addEventListener('click', function () {
        var newPasswordInput = document.getElementById('new_password');
        var newPasswordToggle = document.getElementById('toggleNewPassword');
        newPasswordInput.type = (newPasswordInput.type === 'password') ? 'text' : 'password';
        newPasswordToggle.innerHTML = (newPasswordInput.type === 'password') ? '<i class="mdi mdi-eye-off-outline"></i>' : '<i class="mdi mdi-eye-outline"></i>';
    });
</script>

<?php
if(isset($_POST["btn_vendor"])){
    $id = $_POST['uid'];
    $old_password = isset($_POST["old_password"]) ? $_POST["old_password"] : '';
    $new_password = $_POST["new_password"];

    // Check if the old password is correct if provided
    if (!empty($old_password) && !password_verify($old_password, $row['upassword'])) {
        echo "<script>alert('Old password is incorrect');</script>";
    } else {
        // Old password is correct or not provided, proceed with the update to the new password
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $qry = "UPDATE tbl_user SET name='{$_POST['name']}', email='{$_POST['email']}', contact='{$_POST['contact']}'";
        if (!empty($new_password)) {
            // Update password only if new password is provided
            $qry .= ", password='$new_password_hashed'";
        }
        $qry .= " WHERE user_id=$id";

        if(mysqli_query($conn, $qry)){
            echo "<script>alert('Profile successfully updated');</script>";  
        } else {
            echo "<script>alert('Something wrong! Try again...');</script>";
        }
    }
}
?>
