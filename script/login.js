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

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = loginForm.username.value.trim();
            const password = loginForm.password.value.trim();

            // 通过用户名和密码查找用户
            const dbRef = ref(db, '/userdata/' + username);
            onValue(dbRef, (snapshot)=>{
                if (snapshot.exists()) {  
                    const userData = snapshot.val();  
            
                    // Compare entered password with stored password  
                    const storedPassword = userData.password;  
                    if (password === storedPassword) {  
                        console.log('Login successful!');  
                        // Redirect to homepage or perform necessary login success actions  
                        document.cookie = `username=${encodeURIComponent(username)}; path=/;`; 
                        window.location.href = `index.php?username=${encodeURIComponent(username)}`;
                        //window.location.href = `index.html`;
                    } else {  
                        console.log('Incorrect password!');  
                        // Notify user about incorrect password  
                        loginMessage.textContent = 'Incorrect password!';  
                        loginMessage.style.color = 'red';  
                    }  
                } else {  
                    console.log('User not found!');  
                    // Notify user about non-existing username  
                    loginMessage.textContent = 'User not found!';  
                    loginMessage.style.color = 'red';  
                }  
            })
        });
    }
});
