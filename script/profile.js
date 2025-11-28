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

// Import Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
import { getDatabase, ref, get, set, child } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getDatabase(app)

function getCookieValue(name) {  
    const value = `; ${document.cookie}`;  
    const parts = value.split(`; ${name}=`);  
    if (parts.length === 2) return parts.pop().split(';').shift();  
    return null; // Returns null if the cookie isn't found  
};

document.addEventListener('DOMContentLoaded', function() {
    // const username = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)username\s*\=\s*([^;]*).*$)|^.*$/, "$1")); // 获取用户名
    const username = getCookieValue('username');
    //if (username) {y
        ///console.log('Username from cookie:', username); // 检查 username 值
        const dbRef = ref(db);
        

        // 读取用户数据并填充到表单中
        get(child(dbRef, `userdata/${username}`)).then((snapshot) => {
            if (snapshot.exists()) {
                const userData = snapshot.val();
                console.log('User data:', userData); // 检查获取到的用户数据

                // 填充表单数据
                document.getElementById('username').textContent = username;
                document.getElementById('email').textContent = userData.email || 'No email available';
                document.getElementById('address').textContent = userData.address || 'No address available';
            } else {
                console.log("No data available");
            }
        }).catch((error) => {
            console.error("Error fetching data:", error);
        });
});
