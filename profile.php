<?php
require_once 'include/session.php';
require_once 'include/db.php';

// make sure user is logged in
if (!isLoggedIn()) {
    redirectToLogin();
}

// get user info from database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // user not found, log out and redirect to login
    session_destroy();
    redirectToLogin();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Música</title>
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
                <li class="user-menu">
                    <a href="#" id="userName"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <ul class="dropdown">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="trackOrder.php">Track Order</a></li>
                        <li><a href="logout.php" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="profile-container">
            <div class="avatar-container">
                <img src="images/profile.png" alt="User Avatar" class="user-avatar">
            </div>
            <div class="profile-card">
                <div class="profile-details">
                    <h1 id="username"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <h2>Email:</h2>
                    <p id="email"><?php echo htmlspecialchars($user['email']); ?></p>
                    <h2>Phone:</h2>
                    <p id="phone"><?php echo htmlspecialchars($user['phone']); ?></p>
                    <h2>Address:</h2>
                    <p id="address"><?php echo htmlspecialchars($user['address']); ?></p>
                </div>
                <div class="profile-actions">
                    <a href="edit_profile.php" class="btn">Edit Profile</a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>