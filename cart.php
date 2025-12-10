<?php
require_once 'include/session.php';
require_once 'include/db.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$user_id = $_SESSION['user_id'];

// 处理购物车操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? '';
    
    if ($action === 'update_quantity') {
        $quantity = $_POST['quantity'] ?? 1;
        // 更新购物车数量逻辑
    } elseif ($action === 'remove_item') {
        // 移除商品逻辑
    }
}

// 获取用户购物车商品
// 这里需要根据你的数据库结构来实现
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Música</title>
    <link rel="stylesheet" href="styles/cart.css">
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
        <h1>Shopping Cart</h1>
        
        <?php
        // show cart items
        $cart_items = []; // get cart items from database
        
        if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div id="cart-item-list">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="product-img">
                    <div class="product-details">
                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                        <p>RM <?php echo htmlspecialchars($item['price']); ?></p>
                    </div>
                    <div class="quantity-controls">
                        <button class="subtract">-</button>
                        <span class="quantity"><?php echo htmlspecialchars($item['quantity']); ?></span>
                        <button class="add">+</button>
                    </div>
                    <button class="remove-btn">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="footer">
                <div class="total">
                    <p id="total-price">Total: RM 0.00</p>
                </div>
                <button class="checkout" id="checkout">Check Out</button>
            </div>
        <?php endif; ?>
    </main>
    
    <script>
        document.getElementById('checkout').addEventListener('click', function() {
            window.location.href = 'check_out.php';
        });
    </script>
</body>
</html>