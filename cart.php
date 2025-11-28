<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles/cart.css">
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
                <li><a href="about.php">About</a></li>
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
                        <li><a href="trackOrder.html">TrackOrder</a></li>
                        <li><a href="#" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Cart</h1>
        <!--这下面加了database后，可以不要了-->
        <div id="cart-item-list"></div>
        <!-- <div class="cart-item">
            <img src="images/guitar.jpg" alt="Product Image" class="product-img" id="image">
            <div class="product-details">
                <p id="product_name">Product name</p>
                <p id="price">RM 100.99</p>
            </div>
            <div class="quantity-controls">
                <button id="subtract" >-</button>
                <span id="amount">1</span>
                <button id="add">+</button>
            </div>
        </div> -->
        <!--重复
        <div class="cart-item">
            <input type="checkbox">
            <img src="images/guitar.jpg" alt="Product Image" class="product-img">
            <div class="product-details">
                <p>Product name</p>
                <p>RM 55.99</p>
            </div>
            <div class="quantity-controls">
                <button>-</button>
                <span>1</span>
                <button>+</button>
            </div>
        </div>
        -->
        <div class="footer">
            <div class="total">
                <p id="total-price">Loading ...</p>
            </div>
            <button class="checkout" id="checkout">Check Out</button>
        </div>
    </main>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/cart.js"></script>
</body>
</html>
