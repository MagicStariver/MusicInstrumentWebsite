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
import { getDatabase, ref, set, push } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

function writeUserData(username, email, phone, address, password) {
    const userData = {  
        email: email,  
        phone: phone,  
        address: address,  
        password: password,  
        birthday:'',
    };
    const saveUserData = (path, data) => {  
        const dataRef = ref(db, path);  
        return set(dataRef, data);  
    };  

    // Save data under 'userdata' for generic user listing  
    saveUserData('userdata/' + username, userData)  
        .then(() => {  
            // Save data under 'personal_data/username/personal_information'  
            return saveUserData(`personal_data/${username}/personal_information`, userData);  
        })  
        .then(() => {
            window.location.href = 'login.php'; // Redirect after successful registration  
        })  
        .catch((error) => {  
            console.error('Error saving data:', error);  
            registerMessage.textContent = 'Error registering user!';  
            registerMessage.style.color = 'red';  
        });  
}

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const registerMessage = document.getElementById('registerMessage');

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault(); // 防止表单提交

        // 获取表单数据
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('address').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();

        if (password !== confirmPassword) {
            registerMessage.textContent = 'Passwords do not match!';
            registerMessage.style.color = 'red';
            return;
        } else {
            writeUserData(username, email, phone, address, password);
        }
    });
});
