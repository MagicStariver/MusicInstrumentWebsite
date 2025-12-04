<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];

// 获取当前用户信息（只用于页面显示）
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    redirectToLogin();
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
    <!-- 添加jQuery -->
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
            <!-- 消息容器（由JS动态填充） -->
            <div id="message-container"></div>
            
            <div class="avatar-container">
                <img src="images/profile.png" alt="User Avatar" class="user-avatar">
            </div>
            <h3 id="username"><?php echo htmlspecialchars($user['username']); ?></h3>
            
            <!-- 改为普通表单，无action，由JS处理 -->
            <form id="profileForm" class="profile-form">
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
                    <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
    
    <!-- 引入修改后的JS文件 -->
    <script src="scripts/edit_profile.js"></script>
</body>
</html>