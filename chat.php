<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
include 'conn.php';

if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit();
}

$me = $_SESSION['email'];

// Send message
if (isset($_POST['send'])) {
    $to = $_POST['receiver_email'];
    $message = $_POST['message'];
    $img_path = '';

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $img_name = $_FILES['image']['name'];
        $img_tmp = $_FILES['image']['tmp_name'];
        $img_path = "image/" . time() . '_' . $img_name;
        move_uploaded_file($img_tmp, $img_path);
    }

    $sql = 'INSERT INTO message(sender_email, receiver_email, message, image) VALUES(:sender_email, :receiver_email, :message, :image)';
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'sender_email' => $me,
        'receiver_email' => $to,
        'message' => $message,
        'image' => $img_path
    ]);
}

// Fetch users to chat with
$users_stmt = $conn->prepare('SELECT id, name, email, image FROM "user" WHERE email != :me');
$users_stmt->execute(['me' => $me]);
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

$receiver_email = $_GET['user'] ?? '';
if (!$receiver_email && count($users) > 0) {
    $receiver_email = $users[0]['email'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo-h.svg">
    <title>Private Chat Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || performance.navigation.type === 2) {
            window.location.reload();
        }
    });
</script>

    <!-- <link rel="stylesheet" href="./chatss-.css"> -->
    <link rel="stylesheet" href="./chat1.css">
</head>

<body>
    <button class="hamburger" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar">
        <h2>Users</h2>

        <ul class="user-list">
 <?php
// First, display the logged-in user with an indicator
$logged_in_user_stmt = $conn->prepare('SELECT id, name, email, image FROM "user" WHERE email = :me');
$logged_in_user_stmt->execute(['me' => $me]);
$logged_in_user = $logged_in_user_stmt->fetch(PDO::FETCH_ASSOC);
if ($logged_in_user) {
    $profile_photo = !empty($logged_in_user['image']) ? $logged_in_user['image'] : 'image/default_profile.png';
    echo "<li style='background-color: #475569; cursor: pointer; border-radius:8px; font-weight: bold; color: white;' class='logged-in-user'>";
    echo "<div class='user-link'>";
    echo "<img src='{$profile_photo}' alt='{$logged_in_user['name']}' class='profile-photo' onclick='openModal(this)'>";
    echo "<span>{$logged_in_user['name']} (You)</span>";
    echo "</div>";
    echo "</li>";
}

// Then, display the other users
$other_users_stmt = $conn->prepare('SELECT id, name, email, image FROM "user" WHERE email != :me');
$other_users_stmt->execute(['me' => $me]);
while ($other_user = $other_users_stmt->fetch(PDO::FETCH_ASSOC)) {
    $profile_photo = !empty($other_user['image']) ? $other_user['image'] : 'image/default_profile.png';
    $selected_style = ($other_user['email'] == $receiver_email) ? "style='font-weight:bold; color: #fff;'" : "";

    echo "<li>";
    echo "<a href='chat.php?user={$other_user['email']}' class='user-link' $selected_style>";
    echo "<img src='{$profile_photo}' alt='{$other_user['name']}' class='profile-photo' onclick='openModal(this); event.stopPropagation(); event.preventDefault();'>";
    echo "<span>{$other_user['name']}</span>";
    echo "</a>";
    echo "</li>";
}
?>
        </ul>
        <div class="logout-link">
            <a href='logout'>Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="chat-header">
            <h3>Chat with: <?php echo htmlspecialchars($receiver_email); ?></h3>
        </div>
        <div class="message-area" id="messageArea">
           

               <?php
$msgs_stmt = $conn->prepare('
    SELECT * FROM message
    WHERE (sender_email = :me AND receiver_email = :receiver)
       OR (sender_email = :receiver AND receiver_email = :me)
    ORDER BY time ASC
');
$msgs_stmt->execute(['me' => $me, 'receiver' => $receiver_email]);
while ($msg = $msgs_stmt->fetch(PDO::FETCH_ASSOC)) {
    $cls = $msg['sender_email'] == $me ? "sent" : "received";
    echo "<div class='message $cls' data-message-id='" . htmlspecialchars($msg['id']) . "'>";

    echo "<div class='message-top'>";
    echo "<div class='message-text'>" . htmlspecialchars($msg['message']) . "</div>";

    // Show the forward and delete icons directly
    echo "<div class='message-actions'>";

    // 🗑️ Delete icon, only if the user is the sender
    if ($msg['sender_email'] == $me) {
        echo "<form method='POST' action='delete_message.php?user=" . urlencode($receiver_email) . "' onsubmit='return confirm(\"Delete this message?\");' style='display:inline; margin-right-13px;'>";
        echo "<input type='hidden' name='message_id' value='" . htmlspecialchars($msg['id']) . "'>";
        echo "<button type='submit' class='delete-icon' title='Delete'><i class='fas fa-trash-alt'></i></button>";
        echo "</form>";
    }

    echo "<i class='fas fa-share forward-icon' title='Forward' data-msg-id='" . htmlspecialchars($msg['id']) . "' data-msg-text='" . htmlspecialchars($msg['message']) . "' data-msg-image='" . htmlspecialchars($msg['image']) . "' onclick='forwardMessage(this)'></i>";
    echo "</div>"; // .message-actions

    echo "</div>"; // .message-top

    // Image preview
    if ($msg['image']) {
        echo "<br><img src='" . htmlspecialchars($msg['image']) . "' class='image-message' onclick='openModal(this)'>";
    }

    // Timestamp
    echo "<br><small style='margin-top: -17px; '>" . htmlspecialchars($msg['time']) . "</small>";

    echo "</div>"; // .message
}
?>
        </div>
        <form class="input-area" method="POST" action="chat.php?user=<?php echo htmlspecialchars($receiver_email); ?>" enctype="multipart/form-data">
            <input type="hidden" name="receiver_email" value="<?php echo htmlspecialchars($receiver_email); ?>">
            <input type="text" name="message" id="msgInput" placeholder="Type a message" required>
            <!-- <button type="button" id="emoji-btn">😊</button> -->
            <input type="file" name="image" style="display: none;" id="imageUpload">



            <label for="imageUpload" class="upload-icon" style="    cursor: pointer;
    margin-top: 7px;
    margin-right: 13px;
    margin-left: 12px;">
                <i class="fas fa-image"></i>
            </label>

            <button type="submit" name="send">Send</button>
        </form>
    </div>

    <div id="myModal" class="modal" style="display: none;">
        <img class="modal-content" id="img01" style="margin-bottom: 10px;">
        <div id="caption" style="text-align: center; margin-bottom: 10px;"></div>
        <div id="modalActions" style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
            <span class="close" style="font-size: 20px; font-weight: bold; cursor: pointer; opacity: 0.7; background-color: transparent; border: none; padding: 5px; line-height: 1; text-decoration: none;">✕</span>
            <div style="display: flex;">
                <button id="copyButton" style="background-color:#6c757d; color:#fff; border:none; padding:10px 15px; border-radius:6px; cursor:pointer; font-size:16px; margin-right: 10px;">Copy</button>
                <a id="downloadLink" download style="text-decoration: none;">
                    <button style="background-color:#007bff; color:#fff; border:none; padding:10px 15px; border-radius:6px; cursor:pointer; font-size:16px;">Download</button>
                </a>
            </div>
        </div>
    </div>

    <div id="forwardModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Forward to:</h3>
                <span class="close-forward">&times;</span>
            </div>
            <div id="forwardUserList">
                <?php
$all_users_stmt = $conn->prepare('SELECT id, name, email FROM "user" WHERE email != :me AND email != :receiver');
$all_users_stmt->execute(['me' => $me, 'receiver' => $receiver_email]);
while ($user = $all_users_stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<label><input type='checkbox' name='forward_to[]' value='" . htmlspecialchars($user['email']) . "'> " . htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")</label><br>";
}
?>
            </div>
            <button id="sendForward">Send Forward</button>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
    </script>

    <script src="./chats.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js"></script>
</body>

</html>
