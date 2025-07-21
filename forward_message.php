<?php
session_start();
// include 'conn.php';
include 'conn.php';

header('Content-Type: application/json');

$sender_email = strtolower(trim($_SESSION['email'] ?? ''));
$current_chat_with = strtolower(trim($_SESSION['current_chat_with'] ?? ''));

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$message_id = $data['message_id'] ?? null;
$recipients = $data['recipients'] ?? [];

$response = ['success' => false, 'error' => ''];

if ($sender_email && $message_id && !empty($recipients)) {
    // Retrieve the original message details
    $stmt = $conn->prepare("SELECT message, image FROM message WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $original_message = $result->fetch_assoc();
    $stmt->close();

    if ($original_message) {
        $message_text = $original_message['message'];
        $message_image = $original_message['image'];

        $insert_stmt = $conn->prepare("INSERT INTO message (sender_email, receiver_email, message, image) VALUES (?, ?, ?, ?)");

        $forwarded_count = 0;
        $valid_recipients = false; // Flag to check if at least one valid recipient exists

        foreach ($recipients as $recipient_email) {
            $recipient_email = strtolower(trim($recipient_email));

            // Exclude self and current chat partner
            if ($recipient_email !== $sender_email && $recipient_email !== $current_chat_with) {
                $insert_stmt->bind_param("ssss", $sender_email, $recipient_email, $message_text, $message_image);
                $insert_stmt->execute();
                $forwarded_count++;
                $valid_recipients = true;
            }
        }

        $insert_stmt->close();

        if ($valid_recipients) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = 'Please select at least one other user to forward to.';
        }

    } else {
        $response['error'] = 'Original message not found.';
    }

} else {
    $response['error'] = 'Invalid data received.';
}

echo json_encode($response);
?>