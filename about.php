<?php
require_once 'session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <section class="about">
            <h2>About Música</h2>
            <div class="about-content">
                <div class="about-text">
                    <h3>Our Story</h3>
                    <p>Música was founded in <?php echo date('Y') - 5; ?> with a simple mission: to make quality musical instruments accessible to everyone. From beginners to professional musicians, we believe that everyone deserves the best tools to express their musical creativity.</p>
                    
                    <h3>Our Products</h3>
                    <p>We offer a wide range of musical instruments including guitars, pianos, drums, violins, and much more. All our products are carefully selected for their quality and value.</p>
                    
                    <h3>Why Choose Música?</h3>
                    <ul>
                        <li>Quality guaranteed products</li>
                        <li>Competitive prices</li>
                        <li>Fast and reliable shipping</li>
                        <li>Excellent customer support</li>
                        <li>30-day return policy</li>
                    </ul>
                </div>
                
                <div class="about-stats">
                    <div class="stat">
                        <h4>5000+</h4>
                        <p>Happy Customers</p>
                    </div>
                    <div class="stat">
                        <h4>100+</h4>
                        <p>Products</p>
                    </div>
                    <div class="stat">
                        <h4>5</h4>
                        <p>Years Experience</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>