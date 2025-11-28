<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/EditProfile.css">
    
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
        <section class="user-profile">
            <div class="avatar-container">
                <img src="images/profile.png" alt="User Avatar" class="user-avatar">
            </div>
            <h3 id="username">Username</h3>
            <form class="profile-form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="">                

                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" value="">

                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" value="1989-01-01">

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="123 Main St, City, Country">

                <div class="form-actions">
                    <button type="submit" class="save-btn">Save</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='index.html';">Cancel</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
    <script type="module" src="scripts/editprofile.js"></script>
</body>
</html>
