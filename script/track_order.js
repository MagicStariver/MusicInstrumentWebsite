$(document).ready(function() {
    // 1. 加载订单数据
    loadOrders();
    
    // 2. 加载订单函数
    function loadOrders() {
        $.ajax({
            url: 'api/get_orders.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    // 显示最新订单
                    const latestOrder = response.data[0]; // 假设显示最新的
                    displayOrder(latestOrder);
                } else {
                    // 没有订单
                    showNoOrders();
                }
            },
            error: function() {
                $('#product-list').html('<p>Error loading orders</p>');
            }
        });
    }
    
    // 3. 显示订单
    function displayOrder(order) {
        // 3.1 显示订单基本信息
        const orderInfo = `
            <div class="order-info">
                <p><strong>Order #:</strong> ${order.order_number}</p>
                <p><strong>Date:</strong> ${formatDate(order.created_at)}</p>
                <p><strong>Total:</strong> RM ${order.total_amount}</p>
                <p><strong>Status:</strong> <span class="status-${order.status}">${order.status.toUpperCase()}</span></p>
            </div>
        `;
        
        // 3.2 获取订单商品（需要新API或修改现有API）
        // 暂时显示通用信息
        const productInfo = `
            <div class="product-info">
                <img src="images/guitar.jpg" alt="Product Image">
                <p id="product_name">Order #${order.order_number}</p>
            </div>
        `;
        
        $('#product-list').html(productInfo);
        $('.order-tracking').prepend(orderInfo);
        
        // 3.3 更新跟踪状态
        updateTrackingStatus(order.status);
    }
    
    // 4. 显示无订单
    function showNoOrders() {
        const noOrdersHtml = `
            <div class="no-orders">
                <h3>No Orders Found</h3>
                <p>You haven't placed any orders yet.</p>
                <a href="index.php" class="btn">Start Shopping</a>
            </div>
        `;
        $('#product-list').html(noOrdersHtml);
    }
    
    // 5. 更新跟踪状态（根据你原来的tracking函数）
    function updateTrackingStatus(status) {
        const payment = $('#payment');
        const packing = $('#packing');
        const delivery = $('#delivery');
        const arrived = $('#arrived');
        
        const loader1 = $('#loader1');
        const loader2 = $('#loader2');
        const loader3 = $('#loader3');
        
        // 重置所有
        payment.css('border', '2px dashed #130505');
        packing.css('border', '2px dashed #130505');
        delivery.css('border', '2px dashed #130505');
        arrived.css('border', '2px dashed #130505');
        
        loader1.removeClass('stop-animation');
        loader2.removeClass('stop-animation');
        loader3.removeClass('stop-animation');
        
        // 根据状态更新
        switch(status) {
            case 'arrived':
                payment.css('border', '2px solid #130505');
                packing.css('border', '2px solid #130505');
                delivery.css('border', '2px solid #130505');
                arrived.css('border', '2px solid #130505');
                loader1.addClass('stop-animation');
                loader2.addClass('stop-animation');
                loader3.addClass('stop-animation');
                break;
                
            case 'delivery':
                payment.css('border', '2px solid #130505');
                packing.css('border', '2px solid #130505');
                delivery.css('border', '2px solid #130505');
                loader1.addClass('stop-animation');
                loader2.addClass('stop-animation');
                break;
                
            case 'packing':
                payment.css('border', '2px solid #130505');
                packing.css('border', '2px solid #130505');
                loader1.addClass('stop-animation');
                break;
                
            case 'payment':
                payment.css('border', '2px solid #130505');
                break;
                
            default:
                // 保持虚线
                break;
        }
    }
    
    // 6. 格式化日期
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    
    // 7. 可选：自动刷新订单状态
    // 如果订单还在处理中，可以定时刷新
    if (window.location.search.includes('order_id')) {
        // 如果有特定的订单ID，可以更频繁地检查
        setInterval(loadOrders, 30000); // 每30秒刷新
    }
});