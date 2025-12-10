<?php
require_once 'include/session.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);
    
    $message = "Thank you for your message! We'll get back to you soon.";
    $message_type = 'success';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Música</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <h1>Música</h1>
        <nav>
            <ul class="center-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#product-list">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <ul class="right-menu" id="user-menu">
                <?php if (!isLoggedIn()): ?>
                    <li class="login"><a href="login.php">Login</a></li>
                <?php else: ?>
                    <li class="user-menu">
                        <a href="#" id="userName"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <ul class="dropdown">
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="cart.php">Cart</a></li>
                            <li><a href="trackOrder.php">Track Order</a></li>
                            <li><a href="logout.php" id="logout">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <section class="contact">
            <h2>Contact Us</h2>
            
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Get In Touch</h3>
                    <p>If you have any questions or need assistance, please feel free to contact us:</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <strong>Email:</strong>
                            <p>support@musica.com</p>
                        </div>
                        <div class="contact-item">
                            <strong>Phone:</strong>
                            <p>+012-6551 8737</p>
                        </div>
                        <div class="contact-item">
                            <strong>Address:</strong>
                            <p>123 Music Street, Music City, Kuala Lumpur</p>
                        </div>
                        <div class="contact-item">
                            <strong>Business Hours:</strong>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                               Saturday: 10:00 AM - 4:00 PM<br>
                               Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>