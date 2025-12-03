// 原来的 Firebase 代码
/*
const addtocart = push(ref(db,'/personal_data/'+username+'/cart'));
set(addtocart,{
    productid: productId,
})
*/

// 改为调用 PHP API
document.getElementById('add-to-cart').addEventListener('click', function(event) {
    if(!username) {
        alert('Please log in first!');
        return;
    }
    
    fetch('api/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Added to cart!');
            location.href = "cart.php";
        } else {
            alert('Error: ' + data.message);
        }
    });
});