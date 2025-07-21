<?php
session_start();
include 'conn.php';
include 'functions.php';

// if (isset($_POST['login'])) {
//     $email = validate($_POST['email']);
//     $pass = validate($_POST['password']);

//     // Checking the fields or not
//     // if($email != '' && $pass != ''){

//     //     $query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
//     //     $result = mysqli_query($conn, $query);

//     //     // email checking
//     //     if($result) {
//     //         if (mysqli_num_rows($result) == 1) {

//     //         }
//     //     }
//     // }

//     $check = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' AND password='$pass'");
//     if ($row = mysqli_fetch_assoc($check)) {
//         $_SESSION['email'] = $row['email'];
//         $_SESSION['name'] = $row['name'];
//         $_SESSION['image'] = $row['image'];
//         header("location: chat.php");
//     } else {
//         echo "<script>alert('Invalid login!');</script>";
//     }
// }

if (isset($_POST['login'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Checking the fields are filled or not
    if ($email != '' && $password != '') {
        // Use prepared statements for security
        $query = 'SELECT * FROM "user" WHERE email = :email LIMIT 1';
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $hashedPassword = $row['password'];

            if (!password_verify($password, $hashedPassword)) {
                redirect('login', 'Invalid Password');
            }

            // Storing Authentication Data
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['image'] = $row['image'];

            redirect('chat', 'Logged In Successfully');
        } else {
            redirect('login', 'Invalid Email Address');
        }
    } else {
        redirect('login', 'All feilds are mandatory');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo-h.svg">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="./login1.css">
</head>

<body>


    <nav class="navbar">
        <div class="logo">
            <img src="logo-h.svg" alt="H Logo" style="width: 40px; height: 40px;">
            <span style="margin-left: 10px; font-weight: bold; font-size: 20px; color: #4353B7;">ChatApp</span>
        </div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
        </ul>
        <div class="auth-buttons">
            <button class="login-btn"><a href="login.php">Login</a></button>
        </div>
        <!-- 3-bar Hamburger Icon -->
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </nav>




    <div class="container">
        <div class="container-left">
            <div id="adminForm" class="form-container active">
                <h2 style="     font-weight: 700;   color: Black;  padding-bottom: 19px;
font-size: 25px;"> Login</h2>
                <form action="" method="POST">
                    <input type="email" class="form-control" name="email" id="email" required placeholder="Email">
                    <input style="margin-top:15px;" type="password" class="form-control" name="password" id="password" required placeholder="Password">
                    <button style="margin-top:5px;background-color:#4353B7;" type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
                <div style="margin-top:20px;" class="form-footer">
                    <!-- <p><a href="forgot_password.php">Forgot Password?</a></p> -->
                    <p>No account? <a href="registration">Register</a></p>
                </div>
            </div>
        </div>
        <div class="image-section">
            <div class="welcome-animation">
                <div class="chat-bubble">
                    <div class="bubble-content">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <p class="welcome-text">LOGO</p>
                    </div>
                </div>
                <div class="floating-icons">
                    <!-- <div class="icon">💬</div>
                    <div class="icon">📱</div>
                    <div class="icon">✨</div>
                    <div class="icon">🚀</div> -->
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-container">


            <div class="footer-quick">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#">Admin Panel</a></li>
                </ul>
            </div>

            <div class="footer-support">
                <h2>Support</h2>
                <ul>
                <li><a href="#faq">FAQ's</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#contact">Contact Support</a></li>
                 
                </ul>
            </div>

            <div class="footer-services">
                <h2>Services</h2>
                <ul>
                    <li><a href="#">Chat Messaging</a></li>
                    <li><a href="#">File Sharing</a></li>
                    <li><a href="#">User Management</a></li> 
                    <li><a href="#">Real-time Updates</a></li> 
                    <li><a href="#">Secure Communication</a></li> 
                </ul> 
            </div>

            <div class="footer-contact">
                <h2>Contact</h2>
                <p>johni123456789@gmail.com</p>
                <p>Phone: +1 (587) 969-1883328</p>
                <div class="social-icons">
                    <a href="#" class="fa-brands fa-instagram"></a>
                    <a href="#" class="fa-brands fa-facebook"></a>
                    <a href="#" class="fa-brands fa-linkedin"></a>
                    <a href="#" class="fa-brands fa-twitter"></a>
                </div>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Your POS. All rights reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- External scripts removed for deployment -->
    <script>
        function scrollWithOffset(hash, offset = 108) {
          const el = document.querySelector(hash);
          if (!el) return;
          const y = el.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({ top: y, behavior: 'smooth' });
        }
      
        // Handle scroll after redirect (only if there's a hash)
        window.addEventListener('load', () => {
          if (window.location.hash) {
            setTimeout(() => {
              scrollWithOffset(window.location.hash);
            }, 100); // slight delay ensures DOM/render is ready
          }
        });
      </script>
</body>

</html>