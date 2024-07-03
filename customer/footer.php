</div>
            <!-- / Content -->

          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>


    </div>
    <!-- / Layout wrapper -->

<!-- CHAT BOT-->
<link href="./bot/css/chatBot.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" media="all" href="./bot/css/setup.css">
<link rel="stylesheet" media="all" href="./bot/css/says.css">
<link rel="stylesheet" media="all" href="./bot/css/reply.css">
<link rel="stylesheet" media="all" href="./bot/css/typing.css">
<link rel="stylesheet" media="all" href="./bot/css/input.css">

<style>
    .bubble-container .input-wrap textarea {
		margin: 2%;
        height: 1%;
	}
</style>

<div class="chat-screen p-1 border">
    
    <div class="chat-body w-100 rounded">
        <div id="chat" style="background: #FBFBFB;"></div>
    </div>
</div>

<div class="chat-bot-icon">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square animate"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x "><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
</div>
<!-- END CHAT BOT -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>

<script src="./bot/js/Bubbles.js"></script>
<script src="./bot/js/rm.js"></script>
<script>
    $(document).ready(function () {
        //Toggle fullscreen
        $(".chat-bot-icon").click(function (e) {
            $(this).children('img').toggleClass('hide');
            $(this).children('svg').toggleClass('animate');
            $('.chat-screen').toggleClass('show-chat');
        });
    });
</script>
</html>
<?php

$user = $_SESSION["id"];

include "../database/db.php";

if (isset($_POST["oh"]) && $_POST["oh"] == "Order History") {
    $qry = "
    SELECT o.id, o.vnid, o.start_date, o.end_date, o.time, o.order_date, o.total_amount, v.id AS venue_id, v.name AS venue_name
    FROM tbl_booking o
    INNER JOIN tbl_venue v ON o.vnid = v.id
    WHERE o.cid = $user;
";

    $result = mysqli_query($conn, $qry);

    if (mysqli_num_rows($result) > 0) {
        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response[] = array(
                "id" => $row['id'],
                "vnid" => $row['vnid'],
                "start_date" => $row['start_date'],
                "end_date" => $row['end_date'],
                "time" => $row['time'],
                "order_date" => $row['order_date'],
                "total_amount" => $row['total_amount'],
                "venue_id" => $row['venue_id'],
                "venue_name" => $row['venue_name'],
            );
        }

        // Now $response is an array containing the rows from the query
        // You can format this data as needed for display in the chatbot area

        // For example, let's assume you want to display it as a table
        $table = "<table border='1'>";
        $table .= "<tr><th>ID</th><th>Start Date</th><th>End Date</th><th>Time</th><th>Order Date</th><th>Total Amount</th><th>Venue Name</th></tr>";
        
        foreach ($response as $row) {
            $table .= "<tr>";
            $table .= "<td>{$row["id"]}</td>";
            $table .= "<td>{$row["start_date"]}</td>";
            $table .= "<td>{$row["end_date"]}</td>";
            $table .= "<td>{$row["time"]}</td>";
            $table .= "<td>{$row["order_date"]}</td>";
            $table .= "<td>{$row["total_amount"]}</td>";
            $table .= "<td>{$row["venue_name"]}</td>";
            $table .= "</tr>";
        }
        
        $table .= "</table>";
        
        echo json_encode(array(array("text" => $table)));
        
    } else {
        $response = array(array("text" => "No results found."));
        echo json_encode($response);
    }
} else {
    echo mysqli_error($conn);
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Web Page Title</title>

    <!-- Include your existing stylesheets and dependencies here -->

    <!-- CHAT BOT STYLES -->
    <link href="./bot/css/chatBot.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" media="all" href="./bot/css/setup.css">
    <link rel="stylesheet" media="all" href="./bot/css/says.css">
    <link rel="stylesheet" media="all" href="./bot/css/reply.css">
    <link rel="stylesheet" media="all" href="./bot/css/typing.css">
    <link rel="stylesheet" media="all" href="./bot/css/input.css">


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        #chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #chat-display {
            height: 200px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
        }

        #chat-form {
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #ccc;
        }

        #user-input {
            width: 70%;
            padding: 8px;
            margin-right: 5px;
        }

        .user-message {
            text-align: left;
        }

        .chatbot-message {
            text-align: right;
        }
    </style>
</head>

<body>
    

</body>

</html>