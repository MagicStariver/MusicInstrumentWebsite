$(document).ready(function() {
    let cartItems = []; // store cart items
    let userInfo = {};  // store user info
    
    // 1.  upload checkout data
    loadCheckoutData();
    
    // 2. upload checkout data function
    function loadCheckoutData() {
        // simultaneously get user profile and cart items
        $.when(
            $.ajax({ url: 'api/get_profile.php', method: 'GET', dataType: 'json' }),
            $.ajax({ url: 'api/get_cart.php', method: 'GET', dataType: 'json' })
        ).then(function(profileResponse, cartResponse) {
            const profileData = profileResponse[0];
            const cartData = cartResponse[0];
            
            if (profileData.success && cartData.success) {
                userInfo = profileData.data;
                cartItems = cartData.data || [];
                
                // format checkout page
                generateCheckoutPage();
            } else {
                alert('Failed to load checkout data');
                window.location.href = 'cart.php';
            }
        }).fail(function() {
            alert('Error loading checkout data');
            window.location.href = 'cart.php';
        });
    }
    
    // 3. generate checkout page
    function generateCheckoutPage() {
        // 3.1 show user info
        displayUserInfo();
        
        // 3.2 show cart items
        displayCartItems();
        
        // 3.3 set initial totals
        calculateTotals();
    }
    
    // 4. display user info
    function displayUserInfo() {
        if (userInfo) {
            $('#name').html(`<strong>Name:</strong> ${userInfo.username || 'User'}`);
            $('#address').html(`<strong>Address:</strong> ${userInfo.address || 'No address provided'}`);
            $('#phone').html(`<strong>Phone:</strong> ${userInfo.phone || 'No phone provided'}`);
        }
    }
    
    // display cart items
    function displayCartItems() {
        const container = $('#cart-items-container'); 
        container.empty();
        
        if (cartItems.length === 0) {
            container.html('<p>Your cart is empty</p>');
            return;
        }
        
        cartItems.forEach(function(item, index) {
            const itemHtml = `
                <div class="product-item" data-index="${index}">
                    <div class="product-image">
                        <img src="${item.image_source}" alt="${item.product_name}">
                    </div>
                    <div class="product-description">
                        <p class="product-name">${item.product_name}</p>
                        <p class="product-price"><strong>RM ${item.price.toFixed(2)}</strong></p>
                    </div>
                    <div class="product-quantity">
                        <p>Quantity: <span class="quantity">${item.quantity}</span></p>
                    </div>
                </div>
            `;
            container.append(itemHtml);
        });
    }
    
    // calculate totals
    function calculateTotals() {
        if (cartItems.length === 0) {
            $('#subtotal').text('RM 0.00');
            $('#shipping_fee').text('RM 0.00');
            $('#total').text('RM 0.00');
            return;
        }
        
        // calculate subtotal
        let subtotal = 0;
        cartItems.forEach(function(item) {
            subtotal += item.price * item.quantity;
        });
        
        // calculate shipping fee
        const shippingMethod = $('#shipping-method').val();
        const shippingFee = calculateShippingFee(shippingMethod);
        
        // calculate total
        const total = subtotal + shippingFee;
        
        // update display
        $('#subtotal').text('RM ' + subtotal.toFixed(2));
        $('#shipping_fee').text('RM ' + shippingFee.toFixed(2));
        $('#total').text('RM ' + total.toFixed(2));
    }
    
    // calculate shipping fee based on method
    function calculateShippingFee(method) {
        const rates = {
            'j&t': 4.90,
            'dhl': 5.90,
            'poslaju': 6.90,
            'fedex': 3.90
        };
        return rates[method] || 4.90;
    }
    
    // 4. delivery method change event
    $('#shipping-method').on('change', function() {
        calculateTotals();
    });
    
    // 5. payment method change event
    $('#payment-method').on('change', function() {
        // 可以在这里添加支付方式特定的逻辑
        console.log('Payment method changed to:', $(this).val());
    });
    
    // 6. button checkout event
    $('#check_out').on('click', function(event) {
        event.preventDefault();
        
        if (cartItems.length === 0) {
            alert('Your cart is empty');
            return;
        }
        
        const shippingMethod = $('#shipping-method').val();
        const paymentMethod = $('#payment-method').val();
        
        // validation 
        if (!shippingMethod || !paymentMethod) {
            alert('Please select shipping and payment methods');
            return;
        }
        
        // show loading state
        const checkoutBtn = $(this);
        const originalText = checkoutBtn.text();
        checkoutBtn.text('Processing...').prop('disabled', true);
        
        // repare checkout data
        const checkoutData = {
            shipping_method: shippingMethod,
            payment_method: paymentMethod,
            cart_items: cartItems.map(item => ({
                product_id: item.product_id,
                product_name: item.product_name,
                price: item.price,
                quantity: item.quantity
            }))
        };
        
        // call checkout API
        $.ajax({
            url: 'api/checkout.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(checkoutData),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // successful checkout
                    alert(`Order placed successfully! Order #${response.order_number}`);
                    
                    // redirect to order tracking page
                    window.location.href = 'trackOrder.php?order_id=' + response.order_id;
                } else {
                    // failed checkout
                    alert('Checkout failed: ' + response.message);
                    checkoutBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                alert('Error processing checkout. Please try again.');
                console.error('Checkout error:', error);
                checkoutBtn.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // 7. recalculate totals after slight delay to ensure DOM is ready
    setTimeout(calculateTotals, 100);
});