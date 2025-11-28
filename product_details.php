<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#product-list">Products</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
            <ul class="right-menu" id="user-menu">
                <!-- before login -->
                <li class="login"><a href="login.html">Login</a></li>
                <!-- after login -->
                <li class="user-menu hidden">
                    <a href="#" id="userName">Username</a>
                    <ul class="dropdown">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="cart.html">Cart</a></li>
                        <li><a href="trackOrder.html">Track Order</a></li>
                        <li><a href="#" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>

        <div class="container">
            <!--id我帮你set好了，跟js里面的一样-->
            <img id="product-image" alt="Product Name">
            <h1 id="product-name"></h1>
            <h2>Description:</h2>
            <p id="product-description"></p>
            <p class="price" id="product-price"></p>
            <button type="button" id="add-to-cart">Add to Cart</button>
            <button type="button" id="buy-now">Buy Now</button>
            <!--
            <a href="#" class="btn">Add to Cart</a>
            <a href="check_out.html" class="btn">Buy now</a>
            -->
            
        </div>

    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/productDetails.js"></script>
    
</body>

</html>
