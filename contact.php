<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música</title>
    <link rel="stylesheet" href="styles/style.css">
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
        <section class="contact">
            <h2>Contact Us</h2>
            <p>If you have any questions or need assistance, please feel free to contact us:</p>
            <p>Email: support@musica.com</p>
            <p>Phone: +012-6551 8737</p>
            <p>Address: 123 Music Street, Music City, Kuala Lumpur</p>  
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
</body>
</html>