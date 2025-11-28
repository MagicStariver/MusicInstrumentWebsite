//firebase configuration
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


document.addEventListener("DOMContentLoaded", function() {
    // Get the product ID from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (productId) {
        // Call your function to load product details based on the ID
        loadProductDetails(productId);
    } else {
        console.error("Product ID not found in URL");
    }
});

// Function to fetch and display product details
function loadProductDetails(productId) {
    console.log(productId);
    const product_display = ref (db, '/product/' + productId);
    onValue(product_display, (snapshot)=>{
        const product = snapshot.val();
        console.log(product);
        if (!product) {
            alert('Product not found!');
            return;
        }
        // Display product details in HTML
        document.getElementById('product-name').textContent = product.product_name;
        console.log(product.product_name)
        document.getElementById('product-description').textContent = product.product_details;
        document.getElementById('product-price').textContent = `RM${product.price}`;
        document.getElementById('product-image').src = product.image_source;
        document.getElementById('product-image').alt = product.product_name;

        // Store the product details to use when adding to cart
        document.getElementById('add-to-cart').dataset.product = JSON.stringify(product);
    }, 
    (error) => {
        console.error('Error fetching product details:', error);
        alert('Could not load product details.');
    });
    // fetch(`product%20_details.html?id=${productId}`) 
    //     .then(response => response.json())
    //     .then(product => {
    //         if (product.error) {
    //             alert(product.error);
    //             return;
    //         }
    //         // Display product details in HTML 这里随便你改，id对了的
    //         // document.getElementById('product-name').textContent = product.name;
    //         // document.getElementById('product-description').textContent = product.description;
    //         // document.getElementById('product-price').textContent = `$${product.price}`;
    //         // document.getElementById('product-image').src = `images/${product.image}`;
    //         // document.getElementById('product-image').alt = product.name;
    //         // Store the product details to use when adding to cart
    //         document.getElementById('add-to-cart').dataset.product = JSON.stringify(product);
    //     })
    //     .catch(error => {
    //         console.error('Error fetching product details:', error);
    //     });
}

const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get('id');
if (productId) {
    loadProductDetails(productId);
}

/*
// Function to handle adding the product to the cart
function addToCart() {
    // Retrieve product details from the button's data attribute
    const product = JSON.parse(this.dataset.product);

    // Get existing cart items from local storage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Add new product to cart
    cart.push(product);
    
    // Save updated cart back to local storage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    alert("Product added to cart!");
    
}
*/

/*
// Function to handle purchasing the product
function buyNow() {
    alert("Proceed to checkout!");
    // You can redirect to a checkout page or process payment
}
    */

function getCookieValue(name) {  
    const value = `; ${document.cookie}`;  
    const parts = value.split(`; ${name}=`);  
    if (parts.length === 2) return parts.pop().split(';').shift();  
    return null; // Returns null if the cookie isn't found  
}  

// Attach event listeners to buttons
document.addEventListener('DOMContentLoaded', () => {
    //const productId = 1; // Replace with dynamic product ID if necessary
    loadProductDetails(productId);

    const addToCartButton = document.getElementById('add-to-cart');
    const buyNowButton = document.getElementById('buy-now');
    const username = getCookieValue('username');  

    addToCartButton.addEventListener('click', function(event) {
        if(username == null){
            alert('Please log in first !');
            return;
        }
        else{
            const addtocart = push(ref(db,'/personal_data/'+username+'/cart'));
            set(addtocart,{
                productid: productId,
                })
            location.href="cart.php?id=" + productId;
        }
    });
    
    buyNowButton.addEventListener('click', (event) => {
        if(username == null){
            alert('Please log in first !');
            return;
        }
        else{
            location.href="check_out.php?id=" + productId;
        }
    });
});

