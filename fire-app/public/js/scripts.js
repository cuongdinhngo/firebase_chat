var currentUserLogin;
var usersOnline;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var rooms;
var ajaxSetupHeader = {headers: {'X-CSRF-TOKEN': csrfToken}};
var allUsers = [];
var firebaseConfig = {
    apiKey: "AIzaSyD148Ro1XqPI6rfrlMAR_UK9LuvXHnI1X8",
    authDomain: "ndc-chat-project.firebaseapp.com",
    databaseURL: "https://ndc-chat-project-default-rtdb.firebaseio.com",
    projectId: "ndc-chat-project",
    storageBucket: "ndc-chat-project.appspot.com",
    messagingSenderId: "514376478797",
    appId: "1:514376478797:web:76b0bf6b5f85c233f4ac04"
};
var friends;

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();
const database = firebase.database();
const auth = firebase.auth();


messaging.onMessage(function(payload) {
    console.log("onMessage: " + payload.notification.body);
    const noteTitle = payload.notification.title;
    const noteOptions = payload.notification.body;
    new Notification(noteTitle, noteOptions);
    appendMessage(JSON.parse(payload.notification.body));
    scrollToButtom('.messages');
}, e => {
    console.log(e);
});

function getUserByRoomId() {
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/rooms/get-user-by-room",
        method: "GET",
        async: false,
        data: {
            room_id: window.route.get('room_id')
        }
    }).done(function( response ) {
        allUsers = response;
    }).fail(function( jqXHR, textStatus ) {
        console.log( "getUserByRoomId is Failed" + textStatus );
    }); 
}

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

function getUserLogin() {
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/users/current-user-login",
        method: "GET",
        async: false,
    }).done(function( user ) {
        currentUserLogin = user;
    }).fail(function( jqXHR, textStatus ) {
        console.log( "getUserLogin FAILED " + textStatus );
    });
}

function getTotalUsersOnline() {
    $('#userOnline').html(usersOnline.length);
    listUsersOnline();
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

function displayNotify() {
    let qtyNotify = userNotifications.length;
    if (qtyNotify > 10) {
        $("#notify").css("display", "block");
        $("#notify").css("padding", "2px");
        $('#notify').html('10+');
    }
    if (qtyNotify > 0 && qtyNotify < 10) {
        $("#notify").css("display", "block");
        $('#notify').html(qtyNotify);
    }
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
