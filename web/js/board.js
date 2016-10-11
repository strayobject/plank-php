var ws = new WebSocket('ws://localhost:8080/ws/boards/'+boardUrl);
var title = document.getElementById('title');
var description = document.getElementById('description');
var tasks = document.getElementById('categoryListWrapper');
// submit all messages on enter and clear the input field
document.getElementById('submitTask').addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var data = {"title": title.value, "description": description.value}
    ws.send(JSON.stringify(data));
    title.value = '';
    description.value = '';
    title.focus();
});
// append all received messages to #messages
ws.addEventListener('message', function(e) {
    var message = document.createElement('li');
    var span = document.createElement('span');
    var data = JSON.parse(e.data);
    span.setAttribute('title', data.description);
    span.textContent = data.title;
    message.appendChild(span);
    tasks.appendChild(message);
})
