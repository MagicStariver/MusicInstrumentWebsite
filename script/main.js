document.addEventListener('DOMContentLoaded', () => {
    const buyButtons = document.querySelectorAll('.buy-button');
    const products = document.querySelectorAll('.product');
    const categoryButtons = document.querySelectorAll('.category-button');
    const productsLink = document.querySelector('header nav ul li:nth-child(2) a');
    const mybutton = document.getElementById("back-to-top");
    const userMenu = document.querySelector('.user-menu');
    const userNameElement = document.getElementById('userName');
    const logoutButton = document.getElementById('logout');
    const settingsLink = document.getElementById('settingsLink');

    updateLoginStatus();
    
    function getCookieValue(name) {
        const value = `; ${document.cookie}`;  
        const parts = value.split(`; ${name}=`);  
        if (parts.length === 2) return parts.pop().split(';').shift();  
        return null; // Returns null if the cookie isn't found  
    }

    // 用户菜单点击事件
    userNameElement.addEventListener('click', function(event) {
        event.preventDefault();
        userMenu.classList.toggle('show');
    });

    // 更新登录状态
    
    function updateLoginStatus() {
        //const params = new URLSearchParams(window.location.search);  
        const username = getCookieValue('username');
        const userNameElement = document.getElementById('userName');  
        const userMenu = document.querySelector('.user-menu');  
        const loginMenu = document.querySelector('.login');  

        if (username) {  
            // Display the username  
            userNameElement.textContent = username;  
            userMenu.classList.remove('hidden');
            loginMenu.classList.add('hidden');
        } else {  
            userNameElement.textContent = 'Login';  
            userMenu.classList.add('hidden');
            loginMenu.classList.remove('hidden'); 
    }  

    // Handle logout  
    const logoutElement = document.getElementById('logout');  
    if (logoutElement) {  
        logoutElement.addEventListener('click', (event) => {  
            event.preventDefault();  
            document.cookie = `username=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT`;
            window.location.href = 'login.php';  
        });  
    }
    }
    
    
    // 处理注销事件
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('username'); // 清除用户名
                updateLoginStatus(); // 更新登录状态
                window.location.href = 'login.php'; // 重定向到登录页面
            }
        });
    }
    
    // 设置链接点击事件
    if (settingsLink) {
        settingsLink.addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('username');
                updateLoginStatus();
            }
        });
    }

    // 购买按钮事件处理
        buyButtons.forEach(button => {
            button.addEventListener('click', function() {
                alert('Thank you for your purchase!');
            });
        });

    // 类别筛选功能
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = button.getAttribute('data-category');
            products.forEach(product => {
                product.style.display = (category === 'all' || product.getAttribute('data-category') === category) ? 'block' : 'none';
            });
        });
    });

    // 平滑滚动到 Products 部分
    if (productsLink) {
        productsLink.addEventListener('click', function(event) {
            event.preventDefault(); // 阻止默认跳转行为
            var productsSection = document.getElementById('product-list');
            productsSection.scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Back-to-top 按钮功能
    if (mybutton) {
        window.onscroll = function() { 
            mybutton.style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? 'block' : 'none';
        };
        
        mybutton.addEventListener('click', function() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        });
    }
});