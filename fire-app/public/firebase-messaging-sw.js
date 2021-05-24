// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
    apiKey: "AIzaSyD148Ro1XqPI6rfrlMAR_UK9LuvXHnI1X8",
    authDomain: "ndc-chat-project.firebaseapp.com",
    databaseURL: "https://ndc-chat-project-default-rtdb.firebaseio.com",
    projectId: "ndc-chat-project",
    storageBucket: "ndc-chat-project.appspot.com",
    messagingSenderId: "514376478797",
    appId: "1:514376478797:web:76b0bf6b5f85c233f4ac04"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

console.log('FIREBASE MESSAGING SW ....');
console.log(messaging);

messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };
  
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});