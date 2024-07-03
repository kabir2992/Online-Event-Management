<?php
include "header.php";
include "../database/db.php";
?>
<div class="row">
    <div class="col-12">
        <main class="content">
            <div class="container p-0">

                <div class="card">
                    <div class="row g-0">
                        <div class="col-12 col-lg-5 col-xl-3 border-right p-4">

                            <div class="d-none d-md-block">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <input type="text" class="form-control my-3" placeholder="Search...">
                                    </div>
                                </div>
                            </div>

                            <div class="vendor-data-list">
                            <?php 
                            
                            require_once '../functions.php';

                            $user_id = $_SESSION['id'];
                            $sql = "SELECT user_id,name FROM tbl_user WHERE user_type = 'C';";

                            $result = $conn->query($sql);
                            $counter = 1;

                            if ($result->num_rows > 0) {                                
                                while ($vendor_data = $result->fetch_assoc()) {
                                    $is_new_messages = false;
                                    $is_new_messages = getRecentMessagesOrNot($vendor_data["user_id"],$user_id,$conn);

                                    $notification = '';
                                    if ($is_new_messages) {
                                        $notification = '<span class="mdi mdi-chat-alert" style="color: blueviolet" id="new-message-id-'.$counter.'"></span>';
                                    }

                                    echo '<a class="list-group-item list-group-item-action user-list-main-container border-0 mb-2" id="user-chat-information-'.$counter.'" onclick="getChatData('.$user_id.','.$vendor_data["user_id"].','.$counter.');">
                                        <div class="d-flex align-items-start">
                                            <img src="assets/img/avatars/1.png"
                                                class="rounded-circle mr-1" alt="Profile Picture" width="40" height="40">
                                            <div class="flex-grow-1 ml-3 mt-2 mx-2">
                                                '.$vendor_data["name"].' '.$notification.'
                                            </div>
                                        </div>
                                    </a>';
                                    $counter++;
                                }
                            }
                            ?>
                            </div>
                            <hr class="d-block d-lg-none mt-1 mb-0">
                        </div>
                        <div class="col-12 col-lg-7 col-xl-9" id="main-chat-container">
                            <div class="open-chat-logo">
                                Welcome to Be'Eventful 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php
include "footer.php";
?>

<script>
    // fetch particular chat data 
    function getChatData(sender_id,receiver_id,no) {
        $.ajax({
            url: 'chat-messages.php',
            type: 'POST',
            data: {
                senderId: sender_id,
                receiverId: receiver_id,
                Number: no,
                showChat: "Chat"
            },
            success: function (response) {
                $("#main-chat-container").html(response);
                var id = "#user-chat-information-"+no;
                $(".user-list-main-container").removeClass("active");
                $(id).addClass("active");

                // for removing new message icon
                var new_id = "#new-message-id-"+no;
                if ($(new_id).length > 0) {
                    $(new_id).remove();
                }
            },
            complete: function () {
                $(".users-chat-history-container").scrollTop($(".users-chat-history-container")[0].scrollHeight);
            }
        });
    }

    function getChatDataUpdate(sender_id,receiver_id,no) {
        $.ajax({
            url: 'chat-messages.php',
            type: 'POST',
            data: {
                senderId: sender_id,
                receiverId: receiver_id,
                Number: no,
                updateChats: "Chat"
            },
            success: function (response) {
                $(".users-chat-history-container").html(response);
            },
            complete: function () {
                $(".users-chat-history-container").scrollTop($(".users-chat-history-container")[0].scrollHeight);
            }
        });
    }


    function sendTextMessage(sender_id,receiver_id) {
        var message_text = $("#message-textbox").val().trim();

        if (message_text == "") {
            return;
        }

        $.ajax({
            url: 'chat-messages.php',
            type: 'POST',
            data: {
                senderId: sender_id,
                receiverId: receiver_id,
                messageText: message_text
            },
            success: function (response) {
                $(".users-chat-history-container").append(response);
                $("#message-textbox").val('');
                $(".users-chat-history-container").scrollTop($(".users-chat-history-container")[0].scrollHeight);
            }
        });
    }

    var currentSenderId = '';
    var timeoutId;

    function fetchNewMessages(sender_id, receiver_id ,no) {
        currentSenderId = receiver_id;
        if (currentSenderId != '') {
            // Clear the previous timeout
            clearTimeout(timeoutId);
            
            // Set a new timeout
            timeoutId = setTimeout(function() {
                getChatDataUpdate(sender_id, receiver_id, no);
            }, 5000);
        }
    }
</script>