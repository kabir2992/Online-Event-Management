<?php 
  session_start();
  //error_reporting(0);
  include_once "php/config.php";
  if(!isset($_SESSION['id'])){
    header("location: login.php");
  }
?>
<?php include "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php

            $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE user_id = {$_SESSION['id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <!-- <img src="php/images/<?php //echo $row['img']; ?>" alt=""> -->
          <div class="details">
            <span><?php echo $row['name'] ?></span>
            <p><?php echo $row['chat_status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['user_id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
  <?php include "footer.php"; ?>
</body>
</html>
