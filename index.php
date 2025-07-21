<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp - Welcome</title>
    <link rel="icon" type="image/png" href="logo-h.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login1.css">
    <style>
        body {
            margin-top: 108px; /* Account for fixed navbar */
        }
        .hero-section {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-top: -108px; /* Compensate for body margin */
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 3rem;
            color: #4353B7;
            margin-bottom: 20px;
        }
        .cta-buttons {
            margin-top: 40px;
        }
        .cta-btn {
            margin: 10px;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .cta-btn.primary {
            background: #4353B7;
            color: white;
        }
        .cta-btn.secondary {
            background: transparent;
            color: #4353B7;
            border: 2px solid #4353B7;
        }
        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
        }
        .cta-btn.primary:hover {
            color: white;
        }
        .cta-btn.secondary:hover {
            color: #4353B7;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo-h.svg" alt="H Logo" style="width: 40px; height: 40px; margin-top: -62px;">
            <span style="margin-left: 10px; font-weight: bold; font-size: 20px; color: #4353B7;">ChatApp</span>
        </div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#about">About</a></li>
        </ul>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['email'])): ?>
                <button class="login-btn"><a href="chat.php">Go to Chat</a></button>
            <?php else: ?>
                <button class="login-btn"><a href="login.php">Login</a></button>
            <?php endif; ?>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container">
            <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Welcome to ChatApp</h1>
            <p style="font-size: 1.2rem; margin-bottom: 30px;">Connect with friends and family through secure, real-time messaging</p>
            <div class="cta-buttons">
                <?php if (!isset($_SESSION['email'])): ?>
                    <a href="registration.php" class="cta-btn primary">Get Started</a>
                    <a href="login.php" class="cta-btn secondary">Sign In</a>
                <?php else: ?>
                    <a href="chat.php" class="cta-btn primary">Start Chatting</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="features" style="padding: 80px 0; background: #f8f9fa;">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 50px; color: #4353B7;">Features</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">💬</div>
                        <h4>Real-time Chat</h4>
                        <p>Instant messaging with real-time updates and notifications</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">📱</div>
                        <h4>File Sharing</h4>
                        <p>Share images and files with your contacts easily</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">🔒</div>
                        <h4>Secure</h4>
                        <p>Your conversations are private and secure</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" style="padding: 80px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 style="color: #4353B7; margin-bottom: 30px;">About ChatApp</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        ChatApp is a modern, secure messaging platform designed to help you stay connected 
                        with friends and family. With features like real-time messaging, file sharing, 
                        and user-friendly interface, ChatApp provides the best communication experience.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Built with the latest web technologies, ChatApp ensures fast, reliable, 
                        and secure communication for all your messaging needs.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <div style="font-size: 8rem; color: #4353B7;">🚀</div>
                </div>
            </div>
        </div>
    </section>

    <footer style="background: #2c3e50; color: white; padding: 40px 0; margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>ChatApp</h5>
                    <p>Connect with the world through secure messaging</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; 2025 ChatApp. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 