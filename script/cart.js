$(document).ready(function() {
    // upload cart items
    function loadCart() {
        $.ajax({
            url: 'api/get_cart.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayCart(response.data);
                } else {
                    showEmptyCart();
                }
            },
            error: function() {
                $('#cart-item-list').html('<p class="error">Error loading cart</p>');
            }
        });
    }
    
    // show cart items
    function displayCart(items) {
        const container = $('#cart-item-list');
        container.empty();
        
        if (!items || items.length === 0) {
            showEmptyCart();
            return;
        }
        
        let total = 0;
        
        items.forEach(function(item) {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            const itemHtml = `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image_source}" alt="${item.product_name}" class="product-img">
                    <div class="product-details">
                        <h4>${item.product_name}</h4>
                        <p>RM ${item.price.toFixed(2)} each</p>
                        <p>Subtotal: RM ${itemTotal.toFixed(2)}</p>
                    </div>
                    <div class="quantity-controls">
                        <button class="btn-subtract">-</button>
                        <span class="quantity">${item.quantity}</span>
                        <button class="btn-add">+</button>
                    </div>
                </div>
            `;
            
            container.append(itemHtml);
        });
        
        // update total price
        $('#total-price').text('RM ' + total.toFixed(2));
        
        // bidn button events
        bindCartEvents();
    }
    
    // show empty cart
    function showEmptyCart() {
        $('#cart-item-list').html('<p>Your cart is empty</p>');
        $('#total-price').text('RM 0.00');
    }
    
    // Binding events
    function bindCartEvents() {
        $('.btn-subtract').on('click', function() {
            const itemId = $(this).closest('.cart-item').data('id');
            updateCartItem(itemId, -1);
        });
        
        $('.btn-add').on('click', function() {
            const itemId = $(this).closest('.cart-item').data('id');
            updateCartItem(itemId, 1);
        });
    }
    
    // update cart item quantity
    function updateCartItem(itemId, change) {
        $.ajax({
            url: 'api/update_cart.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                item_id: itemId,
                change: change
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadCart(); // reload cart
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Network error. Please try again.');
            }
        });
    }
    
    //  checkout button
    $('#checkout').on('click', function() {
        window.location.href = 'check_out.php';
    });
    
    //  initial load
    loadCart();
});