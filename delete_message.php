<?php
session_start();
include 'conn.php'; // Fixed path to use local conn.php

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$me = $_SESSION['email'];

if (isset($_POST['message_id'])) {
    $msg_id = intval($_POST['message_id']);

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM message WHERE id = ? AND sender_email = ?");
    mysqli_stmt_bind_param($stmt, "is", $msg_id, $me);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Delete message (and maybe also delete image file)
        $msg_data = mysqli_fetch_assoc($result);
        if (!empty($msg_data['image']) && file_exists($msg_data['image'])) {
            unlink($msg_data['image']); // delete the image from server
        }

        // Use prepared statement for delete operation
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM message WHERE id = ? AND sender_email = ?");
        mysqli_stmt_bind_param($delete_stmt, "is", $msg_id, $me);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
    }
    
    mysqli_stmt_close($stmt);
}

$receiver = $_GET['user'] ?? '';
header("Location: chat.php?user=" . urlencode($receiver));
exit();
