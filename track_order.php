<?php
require_once 'session.php';
require_once 'db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];

// get latest order for the user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$order = $stmt->fetch();

// get order status
$order_status = $order ? $order['status'] : 'payment';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Música</title>
    <link rel="stylesheet" href="styles/Trackorder.css">
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
        <div class="container">
            <?php if ($order): ?>
                <div class="product-info" id="product-list">
                    <img src="<?php echo htmlspecialchars($order['product_image'] ?? 'images/guitar.jpg'); ?>" alt="Product Image">
                    <p id="product_name"><?php echo htmlspecialchars($order['product_name'] ?? 'Product Name'); ?></p>
                </div>
                <div class="order-tracking">
                    <div class="tracking-steps">
                        <div class="tracking-step <?php echo $order_status === 'payment' ? 'active' : ''; ?>" id="payment">
                            Payment Confirm
                        </div>
                        <div id="loader1" class="<?php echo $order_status === 'payment' ? '' : 'stop-animation'; ?>"></div>
                        
                        <div class="tracking-step <?php echo in_array($order_status, ['packing', 'delivery', 'arrived']) ? 'active' : ''; ?>" id="packing">
                            Packing
                        </div>
                        <div id="loader2" class="<?php echo in_array($order_status, ['packing', 'delivery', 'arrived']) ? '' : 'stop-animation'; ?>"></div>
                        
                        <div class="tracking-step <?php echo in_array($order_status, ['delivery', 'arrived']) ? 'active' : ''; ?>" id="delivery">
                            Started Delivery
                        </div>
                        <div id="loader3" class="<?php echo in_array($order_status, ['delivery', 'arrived']) ? '' : 'stop-animation'; ?>"></div>
                        
                        <div class="tracking-step <?php echo $order_status === 'arrived' ? 'active' : ''; ?>" id="arrived">
                            Arrived
                        </div>
                    </div>
                    
                    <div class="order-details">
                        <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['id']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></p>
                        <p><strong>Current Status:</strong> <span class="status-<?php echo $order_status; ?>"><?php echo ucfirst($order_status); ?></span></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-orders">
                    <h2>No Orders Found</h2>
                    <p>You haven't placed any orders yet.</p>
                    <a href="index.php" class="btn">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>    
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>