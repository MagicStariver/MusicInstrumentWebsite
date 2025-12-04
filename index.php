<?php
require 'include/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <h1>Música</h1>
        <nav>
            <ul class="center-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="#product-list">Products</a></li>
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
        <section class="banner">
            <h2>Welcome to Música</h2>
            <p>Your one-stop shop for musical instruments.</p>
            <?php if (isLoggedIn()): ?>
                <p class="welcome-message">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <?php endif; ?>
        </section>

        <section class="search-products">
            <h2>Products</h2>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search for products...">
                <button id="searchButton">Search</button>
            </div>

            <div class="category-container" id="category"></div>

            <div class="product-container">
                <section id="product-list" class="product-list"></section>
            </div>
        </section>
        <button id="back-to-top">Back to Top</button>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Música. All rights reserved.</p>
    </footer>

    <script type="module">
        // need change !!!
        const firebaseConfig = {
            apiKey: "AIzaSyAHW8gPuNSVstSV0ytE8oB5-_3PJKvxgMA",
            authDomain: "muzica-93e9c.firebaseapp.com",
            projectId: "muzica-93e9c",
            storageBucket: "muzica-93e9c.appspot.com",
            messagingSenderId: "559137569600",
            appId: "1:559137569600:web:081ec42350a9f8099658a5",
            measurementId: "G-G5MCSMD8H0",
            databaseURL: "https://muzica-93e9c-default-rtdb.firebaseio.com/"
        };

        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        const product_list = ref(db, '/product');
        onValue(product_list, (snapshot) => {
            const data = snapshot.val();
            if (data) {
                const products = Object.keys(data).map(key => ({
                    id: key,
                    ...data[key]
                }));
                const categories = new Set(products.map(product => product.category));

                displayCategories(Array.from(categories));
                displayProducts(products);

                const categoryButtons = document.querySelectorAll('.category-button');
                categoryButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const selectedCategory = this.getAttribute('data-category');
                        if (selectedCategory === "View All") {
                            displayProducts(products);
                        } else {
                            const filteredProducts = products.filter(product => product.category === selectedCategory);
                            displayProducts(filteredProducts);
                        }
                    });
                });

                document.getElementById('searchButton').addEventListener('click', function() {
                    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                    const filteredProducts = products.filter(product => 
                        product.product_name.toLowerCase().includes(searchTerm)
                    );
                    displayProducts(filteredProducts);
                });

                document.getElementById('searchInput').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const filteredProducts = products.filter(product => 
                        product.product_name.toLowerCase().includes(searchTerm)
                    );
                    displayProducts(filteredProducts);
                });
            } else {
                displayCategories([]);
                displayProducts([]);
            }
        });

        function displayCategories(categories) {
            const categoryContainer = document.getElementById('category');
            categoryContainer.innerHTML = '';
            categoryContainer.innerHTML += "<button class=\"category-button\" data-category=\"View All\">View All</button>";

            categories.forEach(category => {
                const categoryButton = `<button class="category-button" data-category="${category}">${category}</button>`;
                categoryContainer.innerHTML += categoryButton;
            });
        }

        function displayProducts(products) {
            const productContainer = document.getElementById("product-list");
            productContainer.innerHTML = '';

            products.forEach(product => {
                const productDiv = `
                    <div class="product" data-category="${product.category}" data-name="${product.product_name}">
                        <div class="imgdiv">
                        <img src="${product.image_source}" alt="${product.product_name}">
                        </div>
                        <h3>${product.product_name}</h3>
                        <p>Price: RM${product.price}</p>
                        <button class="buy-button" data-product-id="${product.id}">View Details</button>
                    </div>
                `;
                productContainer.innerHTML += productDiv;
            });

            const buyButtons = document.querySelectorAll('.buy-button');
            buyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    location.href = `product_details.php?id=${productId}`;
                });
            });
        }
    </script>
</body>
</html>