<?php
session_start();
// include 'conn.php';
include 'conn.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$current_user_email = $_SESSION['email'];

$sql = "SELECT * FROM user WHERE email != '$current_user_email'";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; }
        .container { width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; }
        .user { padding: 10px; border-bottom: 1px solid #ddd; display: flex; align-items: center; }
        .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .user a { text-decoration: none; color: #333; font-weight: bold; }
        .logout { text-align: right; margin-bottom: 10px; }
        .logout a { text-decoration: none; color: red; }
    </style>
</head>
<body>

<div class="container">
    <div class="logout">
        <a href="logout">Logout</a>
    </div>
    <h3>Select a user to chat with:</h3>

    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <div class="user">
            <img src="image/<?php echo $row['image']; ?>" alt="User Image">
            <a href="chat.php?user=<?php echo $row['email']; ?>">
                <?php echo htmlspecialchars($row['name']); ?>
            </a>
        </div>
    <?php } ?>
</div>

</body>
</html>
