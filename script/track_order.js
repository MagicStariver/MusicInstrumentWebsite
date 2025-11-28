// Firebase configuration
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
console.log(username);
const getorder = ref(db, '/personal_data/' + username + '/order')
onValue(getorder, (snapshot) => {
    const data = snapshot.val();  
    if (data) {
        console.log( data);
        displayOrderStatus(data);
    } else {
        console.log("No data in cart for this user.");  
    }  
}, (error) => {  
    console.error("Error fetching cart data:", error);  
});  

function getCookieValue(name) {  
    const value = `; ${document.cookie}`;  
    const parts = value.split(`; ${name}=`);  
    if (parts.length === 2) return parts.pop().split(';').shift();  
    return null; // Returns null if the cookie isn't found  
}

function displayOrderStatus(orderimage) {
    const productContainer = document.getElementById("product-list");
    productContainer.innerHTML = ''; 
    for (const imageKey in orderimage) {
        const image = orderimage[imageKey];
        const productDiv = `
            <div>
                <img src="${image.image_source}" alt="${image.productName}">
                <p id="product_name">${image.productName}</p>
            </div>
        `;
        productContainer.innerHTML += productDiv;
        tracking(image.status);
        console.log(image.status)
    }
}

function tracking (order_status) {
    if (order_status=="arrived"){
        payment.style="border: 2px solid #130505;" //solid line
        packing.style="border: 2px solid #130505;" //solid line
        delivery.style="border: 2px solid #130505;" //solid line
        arrived.style="border: 2px solid #130505;" //solid line
        document.getElementById("loader1").classList.add("stop-animation");
        document.getElementById("loader2").classList.add("stop-animation");
        document.getElementById("loader3").classList.add("stop-animation");
    }
    else if (order_status=="delivery"){
        payment.style="border: 2px solid #130505;" //solid line
        packing.style="border: 2px solid #130505;" //solid line
        delivery.style="border: 2px solid #130505;" //solid line
        arrived.style="border: 2px dashed #130505;" //dashed line
        document.getElementById("loader1").classList.add("stop-animation");
        document.getElementById("loader2").classList.add("stop-animation");
        document.getElementById("loader3").classList.remove("stop-animation");
    }
    else if (order_status=="packing"){
        payment.style="border: 2px solid #130505;" //solid line
        packing.style="border: 2px solid #130505;" //solid line
        delivery.style="border: 2px dashed #130505;" //dashed line
        arrived.style="border: 2px dashed #130505;" //dashed line
        document.getElementById("loader1").classList.add("stop-animation");
        document.getElementById("loader2").classList.remove("stop-animation");
        document.getElementById("loader3").classList.remove("stop-animation");
    }
    else if (order_status=="payment"){
        payment.style="border: 2px solid #130505;" //solid line
        packing.style="border: 2px dashed #130505;" //dashed line
        delivery.style="border: 2px dashed #130505;" //dashed line
        arrived.style="border: 2px dashed #130505;" //dashed line
        document.getElementById("loader1").classList.remove("stop-animation");
        document.getElementById("loader2").classList.remove("stop-animation");
        document.getElementById("loader3").classList.remove("stop-animation");
    }
}