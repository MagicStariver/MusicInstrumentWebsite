$(document).ready(function() {
    // 加载购物车
    function loadCart() {
        $.ajax({
            url: 'api/get_cart.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayCart(response.data);
                } else {
                    $('#cart-item-list').html('<p>Your cart is empty</p>');
                }
            },
            error: function() {
                $('#cart-item-list').html('<p>Error loading cart</p>');
            }
        });
    }
    
    // 显示购物车
    function displayCart(items) {
        const container = $('#cart-item-list');
        container.empty();
        
        if (!items || items.length === 0) {
            container.html('<p>Your cart is empty</p>');
            $('#total-price').text('RM 0.00');
            return;
        }
        
        let total = 0;
        items.forEach(item => {
            total += item.price * item.quantity;
            container.append(`
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image_source}" alt="${item.product_name}" class="product-img">
                    <div class="product-details">
                        <p>${item.product_name}</p>
                        <p>RM ${item.price.toFixed(2)}</p>
                    </div>
                    <div class="quantity-controls">
                        <button class="subtract">-</button>
                        <span class="quantity">${item.quantity}</span>
                        <button class="add">+</button>
                    </div>
                </div>
            `);
        });
        
        $('#total-price').text('RM ' + total.toFixed(2));
        
        // 绑定数量按钮事件
        $('.subtract').on('click', function() {
            updateQuantity($(this).closest('.cart-item').data('id'), -1);
        });
        
        $('.add').on('click', function() {
            updateQuantity($(this).closest('.cart-item').data('id'), 1);
        });
    }
    
    // 更新数量
    function updateQuantity(itemId, change) {
        $.ajax({
            url: 'api/update_cart.php',
            method: 'POST',
            data: { item_id: itemId, change: change },
            dataType: 'json',
            success: function() {
                loadCart(); // 重新加载
            }
        });
    }
    
    // 结账按钮
    $('#checkout').on('click', function() {
        window.location.href = 'check_out.php';
    });
    
    // 初始加载
    loadCart();
});