<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\Message;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();

require "../middlewares/auth.php";
auth()->checkAccess("/user/chat.php");

require "../utils/messages.php";

if(auth()->accountType != AccountType::TRAINER && !auth()->hasTrainer()) {
    flashWithRedirect(FlashType::ERROR, "Nejprve si vyber trenéra", "/user/index.php");
    return;
}

if(auth()->accountType == AccountType::TRAINER && count(auth()->getAllClients()) < 1) {
    flashWithRedirect(FlashType::ERROR, "Nemáš žádného klienta", "/user/index.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<?php echo renderHead(true) ?>
<body>

<?php require "../components/navbar.php"; ?>

<?php
$chats = Message::getChats(auth());
$activeChat = null;
if (count($chats) > 0) {
    $activeChat = $chats[0];
}

?>

<div class="chat h-100 pt-nav">
    <div id="from" class="<?php echo $_SESSION["id"]?>"></div>
     <div class="chat-sidebar">
         <div class="header">
             <h4>Chat</h4>
         </div>
         <div class="users">
             <?php
             for($i = 0; $i < count($chats); $i++) {
                 $chat = $chats[$i];
                 $lastMessage = $chat["last_message"];
                 $lastMessageType = $chat["last_message_type"];

                 if($lastMessageType == "FILE") {
                     $lastMessage = "File";
                 }

                 $isActive = $activeChat["id"] == $chat["id"];
                 $classes = "user";
                    if($isActive) {
                        $classes .= " active";
                    }

                 echo '
                 <div class="'. $classes .'" id="user-' . $chat["id"] .'" onclick="selectChat('. $chat["id"] . ','.$_SESSION["id"].' )">
                    <img src="'. $chat["avatar"] .'" alt="Avatar">
                    <div class="info">
                        <h5 class="name">
                            '.  $chat["full_name"] .'
                        </h5>
                        <p class="last-message">'.
                            $lastMessage
                        . '</p>
                    </div>
                </div>';
             }
             ?>
    </div>
     </div>

    <div class="chat-with-messages">
        <div class="chat-messages">
            <div class="header">
                <h4>
                    <?php echo $activeChat["full_name"] ?>
                </h4>
            </div>
        </div>

        <div class="chat-input">
            <input type="text" id="message" placeholder="Type a message...">
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>

            <button onclick="openUpload()">
                <i class="fas fa-paperclip"></i>
            </button>


            <input type="file" id="upload" onchange="uploadFile()" hidden="hidden" accept="*/*">
        </div>
    </div>
</div>

<?php require "../components/footer.php"; ?>

</body>
</html>