# REALTIME CHAT: LARAVEL, MySQL, FIREBASE (Authentication, Cloud Messaging and Realtime Database)

Read more detail at [my linkedin](https://www.linkedin.com/pulse/firebase-realtime-chat-laravel-mysql-authentication-cloud-ngo)

1) Go to `fire-docker` folder to start containers
2) Run command: `docker-composer up -d` at `fire-docker`
3) Check running dockers: `docker ps`
4) Make `.env` file: `cp .env .env.example`
5) Update `.env` file at `fire_app` folder
<pre>
FIREBASE_DATABASE_URL=xxxxxxxxxxxxxxxx
FCM_SERVER_KEY=xxxxxxxxxxxxxx"
</pre>
6) Make `firebase_credentials.json` file: `cp firebase_credentials.json firebase_credentials.json.example`
7) Update firebase credential values:
<pre>
{
  "type": "service_account",
  "project_id": "xxxxx-project",
  "private_key_id": "xxxxxxxxxxxxxxxxxxxxxxx",
  "private_key": "-----BEGIN PRIVATE KEY-----\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk-apfma@xxxxxx-project.iam.gserviceaccount.com",
  "client_id": "xxxxxxxxxxx",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-apfma%40xxxxxxx-project.iam.gserviceaccount.com"
}
</pre>
8) Update firebase credential values at ../public/firebase-messaging-sw.js:
<pre>
firebase.initializeApp({
    apiKey: "xxxxxxxxxxxxxxxxxxxxxxx",
    authDomain: "xxxxxxxxxxxxx-project.firebaseapp.com",
    databaseURL: "https://xxxxxxxxxxxxxxxxxx-project-default-rtdb.firebaseio.com",
    projectId: "xxxxxxxxxxxxxx-project",
    storageBucket: "xxxxxxxxxxxxxxxxx-project.appspot.com",
    messagingSenderId: "xxxxxxxxxxxxxxxxxxxxxx",
    appId: "xxxxxxxxxxxxxxxxxxxxxxx"
});
</pre>
9) Update firebase config values at ../public/js/scripts.js:
<pre>
var firebaseConfig = {
    apiKey: "xxxxxxxxxxxxxxxxxxxxxxxxxx",
    authDomain: "xxxxxxxxxxxxxx-project.firebaseapp.com",
    databaseURL: "https://xxxxxxxxxxxxxxxxxxxx-default-rtdb.firebaseio.com",
    projectId: "xxxxxxxxxxxxxxxx-project",
    storageBucket: "xxxxxxxxxxxxxx-project.appspot.com",
    messagingSenderId: "xxxxxxxxxxxxxxxxxxxx",
    appId: "xxxxxxxxxxxxxxxxxxxxxxx"
};
</pre>
10) Start chat project `http://localhost:8000`
