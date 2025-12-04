$(document).ready(function() {
    // 从URL获取产品ID
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (!productId) {
        alert('Product not found');
        window.location.href = 'index.php';
        return;
    }
    
    // 1. 加载产品详情（需要创建 api/get_product.php）
    // 暂时方案：可以在PHP页面直接输出产品数据
    
    // 2. 添加到购物车按钮
    $('#add-to-cart').on('click', function() {
        // 检查是否登录
        $.ajax({
            url: 'api/get_profile.php', // 用来检查登录状态
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // 已登录，添加到购物车
                    addToCart(productId);
                } else {
                    // 未登录，跳转到登录页
                    alert('Please login first');
                    window.location.href = 'login.php';
                }
            },
            error: function() {
                alert('Please login first');
                window.location.href = 'login.php';
            }
        });
    });
    
    // 3. 直接购买按钮
    $('#buy-now').on('click', function() {
        // 同样检查登录
        $.ajax({
            url: 'api/get_profile.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // 先添加到购物车，然后跳转到结账
                    addToCartAndCheckout(productId);
                } else {
                    alert('Please login first');
                    window.location.href = 'login.php';
                }
            }
        });
    });
    
    // 添加到购物车函数
    function addToCart(productId) {
        $.ajax({
            url: 'api/add_to_cart.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                product_id: productId,
                quantity: 1
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Added to cart!');
                    // 可选：跳转到购物车页
                    // window.location.href = 'cart.php';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Failed to add to cart');
            }
        });
    }
    
    // 添加到购物车并结账
    function addToCartAndCheckout(productId) {
        $.ajax({
            url: 'api/add_to_cart.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                product_id: productId,
                quantity: 1
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // 跳转到结账页
                    window.location.href = 'check_out.php';
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
});