// firebase-messaging-sw.js
importScripts("https://www.gstatic.com/firebasejs/11.0.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyAzUqkh3CrdoKDT3rTCzV5ePwh0NqNIXR8",
  authDomain: "ekonsul-pkbijatim.firebaseapp.com",
  projectId: "ekonsul-pkbijatim",
  storageBucket: "ekonsul-pkbijatim.firebasestorage.app",
  messagingSenderId: "773639146943",
  appId: "1:773639146943:web:2d2d5886fb15e436e1e50a",
  measurementId: "G-1BTGTSC5X6"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log("Pesan diterima (background): ", payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: "/icon.png",
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
