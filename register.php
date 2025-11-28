<?php
require_once 'db.php';
require_once 'session.php';

// 如果已经登录，重定向到首页
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // 验证输入
    if ($password !== $confirmPassword) {
        $message = "Passwords do not match";
        $message_type = 'error';
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long";
        $message_type = 'error';
    } else {
        try {
            // 检查用户名是否已存在
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $message = "Username or email already exists";
                $message_type = 'error';
            } else {
                // 创建新用户
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $phone, $address, $hashedPassword]);
                
                $message = "Registration successful! You can now login.";
                $message_type = 'success';
            }
        } catch (PDOException $e) {
            $message = "Registration failed: " . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Música</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>Música</h1>
        <nav>
            <ul>
                <li><a href="login.php">Back to Login</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="register-container">
            <h2>Register</h2>
            
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <button type="submit">Register</button>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>