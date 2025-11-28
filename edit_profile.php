<?php
require_once 'session.php';
require_once 'db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// 获取当前用户信息
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    redirectToLogin();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $fullName = trim($_POST['fullName']);
    $birthday = $_POST['birthday'];
    $address = trim($_POST['address']);
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET email = ?, full_name = ?, birthday = ?, address = ? WHERE id = ?");
        $stmt->execute([$email, $fullName, $birthday, $address, $user_id]);
        
        // 更新会话中的信息
        $_SESSION['email'] = $email;
        
        $message = "Profile updated successfully!";
        $message_type = 'success';
        
        // 重新获取用户信息
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
    } catch (PDOException $e) {
        $message = "Update failed: " . $e->getMessage();
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/EditProfile.css">
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
        <section class="user-profile">
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="avatar-container">
                <img src="images/profile.png" alt="User Avatar" class="user-avatar">
            </div>
            <h3 id="username"><?php echo htmlspecialchars($user['username']); ?></h3>
            <form method="POST" class="profile-form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">

                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday'] ?? '1989-01-01'); ?>">

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

                <div class="form-actions">
                    <button type="submit" class="save-btn">Save</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='profile.php';">Cancel</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>