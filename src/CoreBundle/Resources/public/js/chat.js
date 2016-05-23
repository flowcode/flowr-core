/**
 * Created by juanma on 5/15/16.
 */

socket.on('connect', function () {
    socket.emit('adduser', username, '#general');
});

$('#chat form').submit(function () {
    socket.emit('sendchat', {'appkey': flowr_node_app_id, 'user': username, 'msg': $('#m').val()});
    $('#m').val('');
    return false;
});

function appendMessage(data) {
    var msg = '<b>' + data.user + '</b>: ' + data.msg;
    $('#messages').append('<li>' + msg + '</li>');
    $('#messages').animate({scrollTop: $('#messages').height()});
}

function notifyChat() {
    var chatView = $('#chat');
    if (chatView.hasClass('collapsed')) {
        chatView.addClass('notified');
    } else {
        chatView.removeClass('notified');
    }
}

socket.on('updatechat', function (data) {
    appendMessage(data);
    notifyChat();
});

socket.on('server-message', function (data) {
    appendMessage(data);
});

socket.on('p-' + username, function (data) {
    data.user = '<img src="' + flowr_logo + '" width="15">';
    appendMessage(data);
    notifyChat();
});

socket.on('flowr-' + username, function (data) {
    data.user = '<img src="' + flowr_logo + '" width="15">';
    appendMessage(data);
    responsiveVoice.speak(data.msg, 'Spanish Latin American Female');
    notifyChat();
});

$('#chat-toggle').click(function () {
    var chatView = $('#chat');
    chatView.toggleClass('collapsed');
    if (!chatView.hasClass('collapsed')) {
        chatView.removeClass('notified');
    }
});