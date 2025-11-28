<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js" type="module"></script>
</head>
<body>
    <header>
        <h1>Música</h1>
        <nav>
            <ul>
                <li><a href="login.html">Back to Login</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="register-container">
            <h2>Register</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <button type="submit">Register</button>
                </div>
            </form>
            <p id="registerMessage"></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script type="module" src="scripts/register.js"></script>
</body>
</html>
