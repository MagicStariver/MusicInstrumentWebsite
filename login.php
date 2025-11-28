<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js" type="module"></script>
</head>
<body>
    <header>
        <h1>Música</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="login-container">
            <h2>Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn">Login</button>
                    <a href="register.html" class="btn">Register</a>
                </div>
            </form>
            <p id="loginMessage"></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script type="module" src="scripts/login.js"></script>
</body>
</html>