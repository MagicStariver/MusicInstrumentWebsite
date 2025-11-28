<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    <div class="profile-container">
        <div class="avatar-container">
            <img src="images/profile.png" alt="User Avatar" class="user-avatar">
        </div>
        <div class="profile-card">
            <div class="profile-details">
                <h1 id="username">Username</h1>
                <h2>Email:</h2>
                <p id="email">email@example.com</p>
                <h2>Address:</h2>
                <p id="address">Address: 123 Main St, City, Country</p>
            </div>
    </div>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/profile.js"></script>
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
</body>
</html>
