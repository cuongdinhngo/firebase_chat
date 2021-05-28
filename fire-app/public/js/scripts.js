var currentUserLogin;
var usersOnline;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var rooms;
var ajaxSetupHeader = {headers: {'X-CSRF-TOKEN': csrfToken}};
var allUsers = [];
var firebaseConfig = {
    apiKey: "xxxxxxxxxxxxxxxxxxxxxxxxxx",
    authDomain: "xxxxxxxxxxxxxx-project.firebaseapp.com",
    databaseURL: "https://xxxxxxxxxxxxxxxxxxxx-default-rtdb.firebaseio.com",
    projectId: "xxxxxxxxxxxxxxxx-project",
    storageBucket: "xxxxxxxxxxxxxx-project.appspot.com",
    messagingSenderId: "xxxxxxxxxxxxxxxxxxxx",
    appId: "xxxxxxxxxxxxxxxxxxxxxxx"
};
var friends;

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();
const database = firebase.database();
const auth = firebase.auth();

messaging.onMessage(function(payload) {
    console.log("onMessage: " + payload.notification.body);
    let notificationBody = JSON.parse(payload.notification.body);
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: notificationBody.notifyContent,
        icon: payload.notification.icon,
    };
    new Notification(noteTitle, noteOptions);
    appendMessage(notificationBody.msgContent);
    scrollToButtom('.messages');
}, e => {
    console.log(e);
});

function getFriends() {
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/status",
        method: "GET",
        async: false,
    }).done(function( res ) {
        friends = res;
        listAvailableFriends(friends);
    }).fail(function( jqXHR, textStatus ) {
        console.log( "getFriends is Failed" + textStatus );
    });    
}

function listUsersOnline() {
    let item = '';
    if (allUsers.length > 0) {
        for (const member of allUsers) {
            let you = member.user_id == window.userData.user.id ? "(You)" : "";
            let status = usersOnline.findIndex(item => item.id === member.user_id) > -1 ? "on-circle" : "off-circle";
            item += `<div class="p-2" ><div class="${status}"></div><a href="/users/${member.user_id}">${member.user.name} ${you}</a></div>`;
        } 
    } else {
        for (const user of usersOnline) {
            item += `<div class="p-2"><a href="/users/${user.id}">${user.name}</a></div>`;
        }
    }
    $(".available-users").html(item);
}

function listAvailableFriends(friends) {
    let item = '';
    for (const index in friends) {
        let uid = friends[index].firebase_uid;
        let status = "off-circle";
        item += `<div class="p-2"><div class="${status}" id="${uid}"></div><a href="/users/${friends[index].id}">${friends[index].name}</a></div>`;
        updateUserRealtimeState(uid);
    }
    $(".available-rooms").html(item);
}

function updateUserRealtimeState (uid) {
    let ref = 'status/' + uid;
    database.ref(ref).on('value', snap => {
        let userRealtimeState = snap.val();
        if (userRealtimeState.state == 'online') {
            $(`#${uid}`).addClass('on-circle');
            $(`#${uid}`).removeClass('off-circle');
        } else {
            $(`#${uid}`).removeClass('on-circle');
            $(`#${uid}`).addClass('off-circle');
        }
    });
}

function scrollToButtom(object) {
    $(`${object}`).animate({
            scrollTop: $(`${object}`).get(0).scrollHeight
        }, 1000);
}

function loadMessages(listMessages) {
    for (const property in listMessages) {
        appendMessage(listMessages[property]);
    }
}

function appendMessage(message) {
    let isCurrentUser = currentUserId == message.sender_id || currentUser.id == message.sender_id ? "is-current-user" : "";
    let item = `<div class="message ${isCurrentUser}">
        <div class="message-item user-name">
            ${group[message.sender_id]['name']}:
        </div>
        <div class="message-item text-message msg-container">
            ${message.content}
        </div>
    </div>`;
    $(".messages-content").append(item);
}


function loginThenUpdateFirebaseToken(idTarget) {
    messaging.requestPermission()
    .then(function () {
        return messaging.getToken()
    })
    .then(function(token) {
        console.log(token);
        $(idTarget).val(token);
    }).catch(function (err) {
        console.log('GET DEVICE TOKEN ERROR: '+ err);
    });
}

function redirectTo(url) {
    $(location).attr('href',url);
}
