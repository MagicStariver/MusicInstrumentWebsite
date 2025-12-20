<?php
require_once 'include/session.php';
require_once 'include/db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];

// 获取用户信息（初始显示，JS会更新）
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
    <title>Check Out - Música</title>
    <link rel="stylesheet" href="styles/check_out.css"> 
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
        
        <div class="checkout-info">
            <div class="user-details">
                <div class="user-detail">
                    <p id="name"><strong>Name :</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p id="address"><strong>Address :</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    <p id="phone"><strong>Phone :</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
            </div>

            <div class="product-details">
                <h3>Order Summary</h3>
                <div id="cart-items-container">
                    <p>Loading cart items...</p>
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
                    <p id="subtotal">RM 0.00</p>
                </div>
                <div class="shipping-fee">
                    <p>Shipping Fee</p>
                    <p id="shipping_fee">RM 0.00</p>
                </div>
                <div class="total">
                    <p><strong>Total</strong></p>
                    <p id="total"><strong>RM 0.00</strong></p>
                </div>
            </div>

            <div class="checkout-button">
                <button type="button" id="check_out">Place Order</button>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
    
    <script src="scripts/check_out.js"></script>
</body>
</html>