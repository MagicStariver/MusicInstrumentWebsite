$(document).ready(function() {
    let cartItems = []; // 存储购物车商品
    let userInfo = {};  // 存储用户信息
    
    // 1. 加载用户信息和购物车
    loadCheckoutData();
    
    // 2. 加载结账数据
    function loadCheckoutData() {
        // 同时加载用户信息和购物车
        $.when(
            $.ajax({ url: 'api/get_profile.php', method: 'GET', dataType: 'json' }),
            $.ajax({ url: 'api/get_cart.php', method: 'GET', dataType: 'json' })
        ).then(function(profileResponse, cartResponse) {
            const profileData = profileResponse[0];
            const cartData = cartResponse[0];
            
            if (profileData.success && cartData.success) {
                userInfo = profileData.data;
                cartItems = cartData.data || [];
                
                // 生成结账页面
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
    
    // 3. 生成结账页面
    function generateCheckoutPage() {
        // 3.1 显示用户信息
        displayUserInfo();
        
        // 3.2 显示购物车商品
        displayCartItems();
        
        // 3.3 计算并显示价格
        calculateTotals();
    }
    
    // 显示用户信息
    function displayUserInfo() {
        if (userInfo) {
            $('#name').html(`<strong>Name:</strong> ${userInfo.username || 'User'}`);
            $('#address').html(`<strong>Address:</strong> ${userInfo.address || 'No address provided'}`);
            $('#phone').html(`<strong>Phone:</strong> ${userInfo.phone || 'No phone provided'}`);
        }
    }
    
    // 显示购物车商品
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
    
    // 计算价格
    function calculateTotals() {
        if (cartItems.length === 0) {
            $('#subtotal').text('RM 0.00');
            $('#shipping_fee').text('RM 0.00');
            $('#total').text('RM 0.00');
            return;
        }
        
        // 计算小计
        let subtotal = 0;
        cartItems.forEach(function(item) {
            subtotal += item.price * item.quantity;
        });
        
        // 计算运费（根据选择的配送方式）
        const shippingMethod = $('#shipping-method').val();
        const shippingFee = calculateShippingFee(shippingMethod);
        
        // 计算总计
        const total = subtotal + shippingFee;
        
        // 更新显示
        $('#subtotal').text('RM ' + subtotal.toFixed(2));
        $('#shipping_fee').text('RM ' + shippingFee.toFixed(2));
        $('#total').text('RM ' + total.toFixed(2));
    }
    
    // 计算运费
    function calculateShippingFee(method) {
        const rates = {
            'j&t': 4.90,
            'dhl': 5.90,
            'poslaju': 6.90,
            'fedex': 3.90
        };
        return rates[method] || 4.90;
    }
    
    // 4. 配送方式变更事件
    $('#shipping-method').on('change', function() {
        calculateTotals();
    });
    
    // 5. 支付方式变更事件
    $('#payment-method').on('change', function() {
        // 可以在这里添加支付方式特定的逻辑
        console.log('Payment method changed to:', $(this).val());
    });
    
    // 6. 结账按钮点击事件
    $('#check_out').on('click', function(event) {
        event.preventDefault();
        
        if (cartItems.length === 0) {
            alert('Your cart is empty');
            return;
        }
        
        const shippingMethod = $('#shipping-method').val();
        const paymentMethod = $('#payment-method').val();
        
        // 验证选择
        if (!shippingMethod || !paymentMethod) {
            alert('Please select shipping and payment methods');
            return;
        }
        
        // 显示加载状态
        const checkoutBtn = $(this);
        const originalText = checkoutBtn.text();
        checkoutBtn.text('Processing...').prop('disabled', true);
        
        // 准备结账数据
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
        
        // 提交结账
        $.ajax({
            url: 'api/checkout.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(checkoutData),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // 结账成功
                    alert(`Order placed successfully! Order #${response.order_number}`);
                    
                    // 跳转到订单跟踪页面
                    window.location.href = 'trackOrder.php?order_id=' + response.order_id;
                } else {
                    // 结账失败
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
    
    // 7. 页面加载时计算初始价格
    setTimeout(calculateTotals, 100);
});