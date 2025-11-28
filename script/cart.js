const firebaseConfig = {
    apiKey: "AIzaSyAHW8gPuNSVstSV0ytE8oB5-_3PJKvxgMA",
    authDomain: "muzica-93e9c.firebaseapp.com",
    projectId: "muzica-93e9c",
    storageBucket: "muzica-93e9c.appspot.com",
    messagingSenderId: "559137569600",
    appId: "1:559137569600:web:081ec42350a9f8099658a5",
    measurementId: "G-G5MCSMD8H0",
    databaseURL: "https://muzica-93e9c-default-rtdb.firebaseio.com/"
};

import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
import { getDatabase, ref, set, push, onValue } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

const username = getCookieValue('username')
const cartview = ref (db, '/personal_data/'+ username +'/cart')
onValue(cartview, (snapshot)=>{
    const data = snapshot.val();
    if(data){
        const productkey = Object.keys(data).map(key =>({
            id:key,
            ...data[key]
          }));
        console.log(productkey);
        const productId = productkey.map(item => item.productid); 
        console.log(productId)
        const productDetails = ref (db,'/product/');
        onValue(productDetails, (snapshot)=>{
            const productData = snapshot.val();
            if (productData){
                const allProducts = productData;
                const productDetailsForCart = productId.map(productId => ({  
                    id: productId,  
                    ...allProducts[productId]  
                }));
                const productIds = productDetailsForCart.map(item => item.id); 
                displayProducts(productDetailsForCart)
                console.log(productDetailsForCart);
                console.log(productIds)
                //console.log(productData)
            }
        })
    }
})

document.getElementById("checkout").addEventListener('click', function(event) {
    alert("check out");
    //const productId = this.getAttribute('data-product-id');
    //location.href="check_out.html?id=" + cartId; 这里的cart id need database
    location.href='check_out.php';
}); 

subtractButton = document.getElementById("subtract");
addButton = document.getElementById("add");
amount = document.getElementById("amount");
check_outButtons = document.getElementById("checkout");
total_price = document.getElementById("total-price");

const originalPrice = parseFloat(total_price.innerHTML.replace("RM", ""));

function getCookieValue(name) {  
    const value = `; ${document.cookie}`;  
    const parts = value.split(`; ${name}=`);  
    if (parts.length === 2) return parts.pop().split(';').shift();  
    return null; // Returns null if the cookie isn't found  
}

check_outButtons.addEventListener('click', function(event) {
    alert("check out");
    //const productId = this.getAttribute('data-product-id');
    //location.href="check_out.html?id=" + cartId; 这里的cart id need database
    location.href="check_out.html";
});

//database放这里， 我大概从index html抄来的
function displayProducts(products) {
    const listContainer = document.getElementById("cart-item-list");
    listContainer.innerHTML = ''; // Clear previous content
    let total_price = 0; // Use let instead of const

    products.forEach(product => {
        total_price += parseFloat(product.price); // Update total price
        const Div = `
            <div class="cart-item">
                <img src="${product.image_source}" alt="${product.product_name}" class="product-img" id="image">
                <div class="product-details">
                    <p id="product_name">${product.product_name}</p>
                    <p id="price">RM${product.price}</p>
                </div>
            </div>
        `;
        
        listContainer.innerHTML += Div;
    });

    // Update the total price element
    document.getElementById("total-price").innerHTML = "RM" + total_price.toFixed(2);
}
