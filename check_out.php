<?php
require_once 'session.php';
require_once 'db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];

// 获取用户信息
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 处理结账
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_method = $_POST['shipping_method'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    
    // 这里处理订单创建逻辑
    // 创建订单，清空购物车等
    
    $_SESSION['order_success'] = true;
    header("Location: trackOrder.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out - Música</title>
    <link rel="stylesheet" href="styles/check_out.css">
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
                        <li><a href="logout.php" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Check Out</h1>
        <form method="POST" class="checkout-info">
            <div class="user-details">
                <div class="user-detail">
                    <p id="name"><strong>Name :</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p id="address"><strong>Address :</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    <p id="phone"><strong>Phone :</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
            </div>

            <div class="product-details">
                <h3>Order Summary</h3>
                <!-- 这里动态显示购物车商品 -->
                <div class="product-item">
                    <div class="product-image">
                        <img src="images/guitar.jpg" alt="Product Image">
                    </div>
                    <div class="product-description">
                        <p id="product_name">Product Name</p>
                        <p id="price"><strong>RM 100.99</strong></p>
                    </div>
                    <div class="product-quantity">
                        <p>Quantity: <span id="quantity">1</span></p>
                    </div>
                </div>
            </div>

            <div class="shipping-method">
                <label for="shipping-method"><strong>Shipping Method</strong></label>
                <select id="shipping-method" name="shipping_method" required>
                    <option value="j&t">J & T</option>
                    <option value="dhl">DHL</option>
                    <option value="poslaju">Pos Laju</option>
                    <option value="fedex">FedEx</option>
                </select>
            </div>

            <div class="payment-method">
                <label for="payment-method"><strong>Payment Method</strong></label>
                <select id="payment-method" name="payment_method" required>
                    <option value="credit-card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank-transfer">Bank Transfer</option>
                    <option value="cash-on-delivery">Cash on Delivery</option>
                </select>
            </div>

            <div class="price-summary">
                <div class="subtotal">
                    <p>Subtotal</p>
                    <p id="subtotal">RM 100.99</p>
                </div>
                <div class="shipping-fee">
                    <p>Shipping Fee</p>
                    <p id="shipping_fee">RM 4.90</p>
                </div>
                <div class="total">
                    <p><strong>Total</strong></p>
                    <p id="total"><strong>RM 105.89</strong></p>
                </div>
            </div>

            <div class="checkout-button">
                <button type="submit" id="check_out">Place Order</button>
            </div>
        </form>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
</body>
</html>