<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música</title>
    <link rel="stylesheet" href="styles/check_out.css">
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
                        <li><a href="#" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Check Out</h1>
        <section class="checkout-info">
            <div class="user-details"></div>
            <div class="user-detail">
                <p id="name"><strong>Name :</strong> yuan</p>
                <p id="address"><strong>Address :</strong> 34, Jalan .......</p>
                <p id="phone"><strong>Phone :</strong> 1234567890</p>
            </div>

            <div class="product-details">
                <div class="product-item">
                    <div class="product-image">
                        <img src="images/guitar.jpg" alt="Product Image">
                    </div>
                    <div class="product-description">
                        <p id="product_name"></p>
                        <p id="price"><strong></strong></p>
                    </div>
                    <div class="product-quantity">
                        <p id="quantity">1</p>
                    </div>
                </div>
            </div>

            <div class="shipping-method">
                <label for="shipping-method"><strong>Shipping Method</strong></label>
                <select id="shipping-method" name="shipping-method">
                    <option value="j&t">J & T</option>
                    <option value="dhl">DHL</option>
                    <option value="poslaju">Pos Laju</option>
                    <option value="fedex">FedEx</option>
                </select>
            </div>

            <div class="payment-method">
                <label for="payment-method"><strong>Payment Method</strong></label>
                <select id="payment-method" name="payment-method">
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
                <button type="button" id="check_out">Check Out</button>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/check_out.js"></script>
</body>
</html>
