let userID = 0

function getLastMessageId() {
    const parent = document.querySelector('.chat-messages');
    const lastMessage = parent.lastElementChild;

    if(lastMessage === null) return 0;
    if(lastMessage.id === "") return 0;
    if(!lastMessage.id.startsWith("msg-")) return 0;

    return lastMessage.id.substring(4);
}

function getSelectedChat() {
    const divID = document.getElementById("from")
    const realID = divID.className;
    userID = realID;
    return document.querySelector('.user.active').id.substring(5)
}

function selectChat(chatId) {
    const chat = document.getElementById(`user-${chatId}`);
    if(chat.classList.contains('active')) return;

    const info = chat.querySelector('.info');
    const name = info.querySelector('.name').innerHTML;

    const active = document.querySelector('.user.active');
    if(active) active.classList.remove('active');

    chat.classList.add('active');

    const chatMessagesContainer = document.querySelector('.chat-messages');
    chatMessagesContainer.querySelector('.header').lastElementChild.innerHTML = name;

    chatMessagesContainer.querySelectorAll('.message-group').forEach((message) => {
        message.remove();
    });

    loadNewMessages();
}

function openUpload() {
    document.querySelector('#upload').click();
}

function showInfo(){
    
}

function uploadFile() {
    const file = document.querySelector('#upload').files[0];
    if(!file) return;

    const bodyFormData = new FormData();
    bodyFormData.set('file', file);
    bodyFormData.set('target', getSelectedChat());

    axios.post('../api/messages.php', bodyFormData).then((response) => {
        const success = response.data.success;
        if(!success) {
            Toastify({
                text: response.data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #e82034, #7a000c)",
                stopOnFocus: true,
                onClick: function(){}
            }).showToast();
        }

        document.querySelector('#upload').files.clear();
    });
}

function sendMessage() {
    const message = document.querySelector('#message').value;
    if(message.length < 1) return;

    var bodyFormData = new FormData();
    bodyFormData.set('message', message);
    bodyFormData.set('target', getSelectedChat());

    axios.post('../api/messages.php', bodyFormData).then((response) => {
        const success = response.data.success;
        if(!success && message) {
            Toastify({
                text: response.data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #e82034, #7a000c)",
                stopOnFocus: true,
                onClick: function(){}
            }).showToast();
            return
        }

        if(!success) return;

        document.querySelector('#message').value = '';
    });
}

function loadNewMessages() {
    const after = getLastMessageId();
    axios.get('../api/messages.php', {
        params: {
            after,
            target: getSelectedChat()
        }
    }).then((response) => {
        let messages = response.data.messages;
        const filteredMessages = messages.filter((message) => message.from == userID || message.to == userID);
        response.data.messages = filteredMessages
        renderMessages(response);
    });
}

setInterval(loadNewMessages, 500);

function renderMessages(response) {
    if (response.data.messages === undefined) return;
    if(response.data.messages.length < 1) return;
    const userId = response.data.user_id;

    if(response.data.target_id +"" !== getSelectedChat()) return;

    let messages = response.data.messages;
    messages.forEach((message) => {
        const isOwnMessage = message.from === userId;
        renderMessage(message, isOwnMessage);
    });

    const lastMessage = messages[messages.length - 1];
    let displayLastMessage = lastMessage.value;
    if(lastMessage.type === "FILE") {
        displayLastMessage = "File";
    }

    document.getElementById("user-"+ getSelectedChat()).querySelector('.last-message').innerHTML = displayLastMessage;

    scrollToBottom();
}

function renderMessage(message, isOwnMessage = true) {
    if(document.querySelector(`.message-group[id="msg-${message.message_id}"]`)) return;
    const messageElement = document.createElement('div');

    messageElement.setAttribute('class', isOwnMessage ? 'message-group right' : 'message-group');
    messageElement.setAttribute('id', `msg-${message.message_id}`);

    if(message.type === "FILE") {
        const fileName = message.value.split("/").pop();
        messageElement.innerHTML = `
            <img src="/storage/avatars/${message.avatar || 'default.webp'}">
                <div class="message-file">
                    <i class="fas fa-file icon"></i>
                    <div class="info">
                        <p>${fileName}</p>
                        <span>${formatTime(message.created_at)}</span>
                    </div>

                    <a class="download" href="${message.value}" download>
                        <i class="fas fa-download"></i>
                    </a>
                </div>
        `;
    } else {
        messageElement.innerHTML = `<img src="/storage/avatars/${message.avatar || 'default.webp'}">
                        <div class="message">
                            <p>
                                ${message.value}
                            </p>
                            <span class="time">
                                ${formatTime(message.created_at)}
                            </span>
                        </div>`;
    }


    const parent = document.querySelector('.chat-messages');
    parent.appendChild(messageElement);
}

function scrollToBottom() {
    const parent = document.querySelector('.chat-messages');
    parent.scrollTop = parent.scrollHeight;
}

function formatTime(createdAt) {
    try {
        const date = new Date(createdAt);
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const yesterday = new Date(today.getTime() - (24 * 60 * 60 * 1000));

        if (date.toDateString() === today.toDateString()) {
            return formatDate(date, "H:i");
        } else if (date.toDateString() === yesterday.toDateString()) {
            return "yesterday at " + formatDate(date, "H:i");
        } else {
            return formatDate(date, "d.m.Y H:i");
        }
    } catch (e) {
        return "Error: " + e.message;
    }
}

function formatDate(date, format) {
    const pad = (value) => value < 10 ? "0" + value : value;

    const replacements = {
        d: pad(date.getDate()),
        m: pad(date.getMonth() + 1),
        Y: date.getFullYear(),
        H: pad(date.getHours()),
        i: pad(date.getMinutes()),
        s: pad(date.getSeconds()),
    };

    return format.replace(/([d|m|Y|H|i|s])/g, (match, group) => replacements[group]);
}