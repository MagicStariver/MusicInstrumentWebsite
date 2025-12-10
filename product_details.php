<?php
require_once 'include/session.php';

$product_id = $_GET['id'] ?? '';

if (empty($product_id)) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/product_details.css">
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js" type="module"></script>
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
        <div class="container">
            <img id="product-image" alt="Product Name">
            <h1 id="product-name"></h1>
            <h2>Description:</h2>
            <p id="product-description"></p>
            <p class="price" id="product-price"></p>
            
            <?php if (isLoggedIn()): ?>
                <button type="button" id="add-to-cart">Add to Cart</button>
                <button type="button" id="buy-now">Buy Now</button>
            <?php else: ?>
                <p class="login-prompt">Please <a href="login.php">login</a> to purchase products.</p>
                <button type="button" disabled>Add to Cart</button>
                <button type="button" disabled>Buy Now</button>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>
    
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
        import { getDatabase, ref, get } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAHW8gPuNSVstSV0ytE8oB5-_3PJKvxgMA",
            authDomain: "muzica-93e9c.firebaseapp.com",
            databaseURL: "https://muzica-93e9c-default-rtdb.firebaseio.com",
            projectId: "muzica-93e9c",
            storageBucket: "muzica-93e9c.appspot.com",
            messagingSenderId: "559137569600",
            appId: "1:559137569600:web:081ec42350a9f8099658a5",
            measurementId: "G-G5MCSMD8H0"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // get product ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');

        if (productId) {
            loadProductDetails(productId);
        }

        async function loadProductDetails(productId) {
            try {
                const productRef = ref(db, 'product/' + productId);
                const snapshot = await get(productRef);
                
                if (snapshot.exists()) {
                    const product = snapshot.val();
                    displayProductDetails(product);
                } else {
                    document.getElementById('product-name').textContent = 'Product not found';
                }
            } catch (error) {
                console.error('Error loading product:', error);
                document.getElementById('product-name').textContent = 'Error loading product';
            }
        }

        function displayProductDetails(product) {
            document.getElementById('product-image').src = product.image_source;
            document.getElementById('product-image').alt = product.product_name;
            document.getElementById('product-name').textContent = product.product_name;
            document.getElementById('product-description').textContent = product.description || 'No description available';
            document.getElementById('product-price').textContent = 'RM ' + product.price;

            // add event listeners for buttons
            document.getElementById('add-to-cart').addEventListener('click', function() {
                addToCart(product);
            });

            document.getElementById('buy-now').addEventListener('click', function() {
                buyNow(product);
            });
        }

        function addToCart(product) {
            alert('Added to cart: ' + product.product_name);
        }

        function buyNow(product) {
            window.location.href = `check_out.php?product_id=${productId}&quantity=1`;
        }
    </script>
</body>
</html>