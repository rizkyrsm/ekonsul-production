import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.0/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.14.0/firebase-messaging.js";

const firebaseConfig = {
    apiKey: "AIzaSyAzUqkh3CrdoKDT3rTCzV5ePwh0NqNIXR8",
    authDomain: "ekonsul-pkbijatim.firebaseapp.com",
    projectId: "ekonsul-pkbijatim",
    storageBucket: "ekonsul-pkbijatim.firebasestorage.app",
    messagingSenderId: "773639146943",
    appId: "1:773639146943:web:2d2d5886fb15e436e1e50a",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

async function requestPermissionAndSaveToken() {
    console.log("Meminta izin notifikasi...");
    const permission = await Notification.requestPermission();
    console.log("Status izin:", permission);

    if (permission === "granted") {
        console.log("Izin diberikan ✅");
        const currentToken = await getToken(messaging, {
            vapidKey: "BAcr6gDDJEX4KM4tFXq1dvojZ_EQ2Ltg-mizaQFC2MaW343Zecxq4AOVtLSwCEiHzNfuwcpQKEoERqVbFeUNcOc",
        });
        if (currentToken) {
            console.log("Token FCM kamu:", currentToken);
            // Kirim token ke server
            await fetch("/save-fcm-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ fcm_token: currentToken }),
            });
        }
    } else {
        console.warn("Notifikasi tidak diizinkan oleh user.");
    }
}

// Jalankan otomatis saat halaman dimuat
requestPermissionAndSaveToken();

onMessage(messaging, (payload) => {
    console.log("Pesan diterima:", payload);
    alert(payload.notification.title + " - " + payload.notification.body);
});
