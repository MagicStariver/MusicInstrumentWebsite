<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música</title>
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
                <li><a href="#product-list">Products</a></li>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const username = params.get('username');
            const userNameElement = document.getElementById('userName');
            const userMenu = document.querySelector('.user-menu');
            const loginMenu = document.querySelector('.login');

            userNameElement.addEventListener('click', function(event) {
                event.preventDefault();
                userMenu.classList.toggle('show');
            });

            if (username) {
                // 显示用户名
                userNameElement.textContent = username;
                userMenu.classList.remove('hidden');
                loginMenu.classList.add('hidden');
            } else {
                userNameElement.textContent = 'Login';
                userMenu.classList.add('hidden');
                loginMenu.classList.remove('hidden');
            }

            // 处理注销
            const logoutElement = document.getElementById('logout');
            if (logoutElement) {
                logoutElement.addEventListener('click', (event) => {
                    event.preventDefault();
                    document.cookie = `username=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT`;
                    window.location.href = 'index.html';
                });
            }
        });
    </script>
    <main>
        <section class="banner">
            <h2>Welcome to Música</h2>
            <p>Your one-stop shop for musical instruments.</p>
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
        <p>&copy; 2024 Música. All rights reserved.</p>
    </footer>
    <script type="module">
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

        // 绑定事件监听器到类别按钮
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

        // 搜索按钮点击事件
        document.getElementById('searchButton').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filteredProducts = products.filter(product => 
                product.product_name.toLowerCase().includes(searchTerm)
            );
            displayProducts(filteredProducts);
        });

        // 实时搜索
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredProducts = products.filter(product => 
                product.product_name.toLowerCase().includes(searchTerm)
            );
            displayProducts(filteredProducts);
        });
    } else {
        displayCategories([]);
        displayProducts([]); // 如果没有数据则不显示
    }
});

        function displayCategories(categories) {
            const categoryContainer = document.getElementById('category');
            categoryContainer.innerHTML = ''; // 清除已有类别

            // 在开头添加“查看全部”选项
            categoryContainer.innerHTML += "<button class=\"category-button\" data-category=\"View All\">View All</button>";

            categories.forEach(category => {
                const categoryButton = `<button class="category-button" data-category="${category}">${category}</button>`;
                categoryContainer.innerHTML += categoryButton;
            });
        }

        function displayProducts(products) {
            const productContainer = document.getElementById("product-list");
            productContainer.innerHTML = ''; // 清除之前的内容

            // 遍历产品数组生成HTML内容
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
                    location.href = `product%20_details.html?id=${productId}`;
                });
            });
        }
    </script>
</body>
</html>
