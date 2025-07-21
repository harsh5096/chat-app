<?php
include 'conn.php';
include 'functions.php';

if (isset($_POST['registration'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_store = "image/" . $image; // changed 'uploads/' to 'image/'
    move_uploaded_file($image_tmp, $image_store);

    $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);


    $check = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered.');</script>";
    } else {
        $sql = "INSERT INTO user(name,email,password,image) VALUES('$name','$email','$bcrypt_password','$image_store')";

        mysqli_query($conn, $sql);
        echo "<script>alert('Registration Successful');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="./login1.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo-h.svg" alt="H Logo" style="width: 40px; height: 40px;">
            <span style="margin-left: 10px; font-weight: bold; font-size: 20px; color:#4353B7;">ChatApp</span>
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
                <h2 style="font-weight: 700;    color: Black;  padding-bottom: 19px;
font-size: 25px;"> Create Account</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" class="form-control" required placeholder="Full Name">
                    <input type="email" name="email" class="form-control" required placeholder="Email">
                    <input type="password" name="password" class="form-control" required placeholder="Password">
                    <input type="file" name="image" class="form-control" required>
                    <!-- <input type="file" name="image" class="form-control" required> -->
                    <button style="background-color:#4353B7;" type="submit" name="registration" class="btn btn-primary w-100">Register</button>
                </form>
                <div style="margin-bottom:20px;" class="form-footer mt-3">
                    Already have an account? <a href="login">Login</a>
                </div>
            </div>
        </div>
        <div class="image-section">
        <p class="welcome-text">LOGO</p>
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
                    <li><a href="#">Inventory Management</a></li>
                    <li><a href="#">Sales Reporting</a></li>
                    <!-- <li><a href="#">Customer Loyalty Programs</a></li> -->
                    <li><a href="#">Online Ordering Integration</a></li>
                    <li><a href="#">Payment Processing</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h2>Contact</h2>
                <p>johni123456789@gmail.com</p>
                <p>Phone: +1 (587) 969-1883328</p>
                <p>Suite 240 N - 3015 5 <br> Avenue NE Calgary AB T2A 6C9</p>
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