$(document).ready(function() {
    // 从URL获取产品ID
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (!productId) {
        alert('Product not found');
        window.location.href = 'index.php';
        return;
    }
    
    // Add to cart button
    $('#add-to-cart').on('click', function() {
        //  check login status first
        $.ajax({
            url: 'api/get_profile.php', // check if logged in
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // successfully logged in, add to cart
                    addToCart(productId);
                } else {
                    // not logged in
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
    
    //  Buy now button
    $('#buy-now').on('click', function() {
        //  check login status first
        $.ajax({
            url: 'api/get_profile.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    //  logged in, add to cart and go to checkout
                    addToCartAndCheckout(productId);
                } else {
                    alert('Please login first');
                    window.location.href = 'login.php';
                }
            }
        });
    });
    
    //  add to cart function
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
                    //  redirect to cart page
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
    
    //  add to cart and go to checkout
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
                    //  redirect to checkout page
                    window.location.href = 'check_out.php';
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
});