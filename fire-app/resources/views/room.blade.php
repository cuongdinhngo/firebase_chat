@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <div class="chat">
                  <div class="chat-title">
                    <h1>Chatroom</h1>
                  </div>
                  <div class="messages">
                    <div class="messages-content">
                    </div>
                  </div>
                  <div class="message-box">
                    <input
                      type="text"
                      class="message-input"
                      placeholder="Type message..."
                      id="msgContent"
                    />
                    <input type="hidden" name="device_token" value="{{$receiver->device_token}}" id="deviceToken"/>
                    <input type="hidden" name="receiver_id" value="{{$receiver->id}}" id="receiverId"/>
                    <input type="hidden" name="sender_id" value="{{$sender->id}}" id="senderId"/>
                    <button type="button" class="message-submit" id="btnSend">
                      Send
                    </button>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-md-2">
            <div class="users-online">
                <button type="button" class="btn btn-primary">
                    Your friends
                </button>
            </div>
            <div class="user-rooms">
                <div class="d-flex flex-column mb-3 available-rooms">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_script')
<script>
    var currentUserId = <?php echo $sender->id; ?>;
    var group = <?php echo json_encode($group);?>;
    var listMessages = <?php echo $messages->toJson();?>;

    $(document).ready(function() {
        loadMessages(listMessages);
        scrollToButtom('.messages');
    });


    $('#btnSend').click(function(){
        $.ajaxSetup(ajaxSetupHeader);
        var messageContent = $("#msgContent").val();
        $.ajax({
            url: "/messages/chat",
            method: "POST",
            data: { 
                message : $("#msgContent").val(),
                sender_id: $("#senderId").val(),
                receiver_id: $("#receiverId").val(),
                device_token: $("#deviceToken").val(),
            }
        }).done(function( msg ) {
            console.log(msg);
            appendMessage(msg['message']);
            scrollToButtom('.messages');
            $("#msgContent").val("");
        }).fail(function( jqXHR, textStatus ) {
            console.log( "sendChatMessage FAILED " + textStatus );
        });
    });

</script>

@endsection
