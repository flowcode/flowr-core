/**
 * Created by juanma on 5/15/16.
 */

socket.on('connect', function () {
    socket.emit('adduser', username, '#general');
});

$('#chat form').submit(function () {
    socket.emit('sendchat', {'user': username, 'msg': $('#m').val()});
    $('#m').val('');
    return false;
});

function appendMessage(data) {
    var msg = '<b>' + data.user + '</b>: ' + data.msg;
    $('#messages').append('<li>' + msg + '</li>');
    $('#messages').animate({scrollTop: $('#messages').height()});
}

socket.on('updatechat', function (data) {
    appendMessage(data)
});

socket.on('p-' + username, function (data) {
    data.user = '<img src="'+ flowr_logo +'" width="15">';
    appendMessage(data)
});

$('#chat-toggle').click(function () {
    $('#chat').toggleClass('collapsed');
});