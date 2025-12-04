$(document).ready(function() {
    // 1.  unload orders on page load
    loadOrders();
    
    // 2.  unload orders function
    function loadOrders() {
        $.ajax({
            url: 'api/get_orders.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    // show latest order
                    const latestOrder = response.data[0]; // if multiple orders, show the latest
                    displayOrder(latestOrder);
                } else {
                    // no orders found
                    showNoOrders();
                }
            },
            error: function() {
                $('#product-list').html('<p>Error loading orders</p>');
            }
        });
    }
    
    // 3.  display order details
    function displayOrder(order) {
        // 3.1 show order info
        const orderInfo = `
            <div class="order-info">
                <p><strong>Order #:</strong> ${order.order_number}</p>
                <p><strong>Date:</strong> ${formatDate(order.created_at)}</p>
                <p><strong>Total:</strong> RM ${order.total_amount}</p>
                <p><strong>Status:</strong> <span class="status-${order.status}">${order.status.toUpperCase()}</span></p>
            </div>
        `;
        
        // 3.2  show product info
        const productInfo = `
            <div class="product-info">
                <img src="images/guitar.jpg" alt="Product Image">
                <p id="product_name">Order #${order.order_number}</p>
            </div>
        `;
        
        $('#product-list').html(productInfo);
        $('.order-tracking').prepend(orderInfo);
        
        // 3.3 update tracking status
        updateTrackingStatus(order.status);
    }
    
    // 4. show no orders message
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
    
    // 5. update tracking status UI
    function updateTrackingStatus(status) {
        const payment = $('#payment');
        const packing = $('#packing');
        const delivery = $('#delivery');
        const arrived = $('#arrived');
        
        const loader1 = $('#loader1');
        const loader2 = $('#loader2');
        const loader3 = $('#loader3');
        
        // reset all to default
        payment.css('border', '2px dashed #130505');
        packing.css('border', '2px dashed #130505');
        delivery.css('border', '2px dashed #130505');
        arrived.css('border', '2px dashed #130505');
        
        loader1.removeClass('stop-animation');
        loader2.removeClass('stop-animation');
        loader3.removeClass('stop-animation');
        
        //  highlight based on status
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
                // status unknown, do nothing
                break;
        }
    }
    
    // 6.   date formatting helper
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    
    // 7.  can choose to auto-refresh the order status
    //  here we set interval to refresh every 1 minute
    if (window.location.search.includes('order_id')) {
        // If there is a specific order ID, it can be checked more frequently.
        setInterval(loadOrders, 30000); //  30 seconds
    }
});