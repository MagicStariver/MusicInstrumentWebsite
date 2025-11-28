<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música</title>
    <link rel="stylesheet" href="styles/Trackorder.css">
    <link rel="stylesheet" href="styles/style.css">
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
                        <li><a href="trackOrder.html">TrackOrder</a></li>
                        <li><a href="#" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="product-info", id="product-list">
                <img src="images/guitar.jpg" alt="Product Image">
                <p id="product_name">Product Name</p>
            </div>
            <div class="order-tracking">
                <div class="tracking-steps">
                    <div class="tracking-step" id="payment">Payment Confirm</div>
                    <div id="loader1"></div>
                    <div class="tracking-step" id="packing">Packing</div>
                    <div id="loader2"></div>
                    <div class="tracking-step" id="delivery">Started Delivery</div>
                    <div id="loader3"></div>
                    <div class="tracking-step" id="arrived">Arrived</div>
                </div>
            </div>
        </div>    
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/tack_order.js"></script>
</body>
</html>
