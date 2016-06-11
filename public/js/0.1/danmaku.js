/**
 * Created by Volio on 2016/5/22.
 */
var Realtime = AV.Realtime;
var onlineNum = document.getElementById('onlineNum');
var inputSend = document.getElementById('danmaku');
var sendBtn = document.getElementById('send-btn');
var room;
var firstFlag = true;
var clientId;

bindEvent(sendBtn, 'click', sendMsg);
bindEvent(document.body, 'keydown', function (e) {
    if (e.keyCode === 13) {
        sendMsg();
    }
});

function bindEvent(dom, eventName, fun) {
    if (window.addEventListener) {
        dom.addEventListener(eventName, fun);
    } else {
        dom.attachEvent('on' + eventName, fun);
    }
}

function danmakuInit(appId, roomId) {
    $.ajax({
        type: 'GET',
        url: '../api/user/getinfo?type=name',
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.name) {
                clientId = data.name;
            }
            main(appId, roomId, clientId);
        },
        error: function () {
            clientId = '游客';
            main(appId, roomId, clientId);
        }
    });
}

function main(appId, roomId, clientId) {

    var realtime = new Realtime({
        appId: appId,
        region: 'cn' // 美国节点为 "us"
    });

    showMessage('系统', '正在连接弹幕服务器...');
    realtime.createIMClient(clientId).then(function (client) {
        //接收消息
        client.on('message', function(message) {
            showMessage(message.from, message.text);
        });

        return client.getConversation(roomId);
    }).then(function (conversation) {

        firstFlag = false;
        return conversation.join();

    }).then(function (conversation) {

        showMessage('系统', '弹幕服务器连接成功！');
        room = conversation;
        showOnlineNum();
        setInterval(showOnlineNum,30000);
        conversation.queryMessages({
            limit: 15, // limit 取值范围 1~1000，默认 20
        }).then(function(messages) {
            var l = messages.length;
            for (var i = 0; i < l; i++) {
                showMsg(messages[i]);
            }
        })

    }).catch(console.error);

    realtime.on('disconnect', function () {
        console.log('网络连接已断开');
    });
    realtime.on('schedule', function (attempt, delay) {
        console.log(delay + 'ms 后进行第' + (attempt + 1) + '次重连');
    });
    realtime.on('retry', function (attempt) {
        console.log('正在进行第' + attempt + '次重连');
    });
    realtime.on('reconnect', function () {
        console.log('网络连接已恢复');
    });
}

function showMessage(name, data) {
    if (data) {
        console.log(name, data);
        msg = '<span class="user-danmaku-name"><i class="fa fa-comment-o"></i> ' + encodeHTML(name) + '</span> : <span>' + encodeHTML(data) + '</span>'
    }
    var div = document.createElement('div');
    div.className = 'user-danmaku';
    div.innerHTML = msg;
    printWall.appendChild(div);
    if (printWall.childNodes.length > 150) {
        printWall.removeChild(printWall.childNodes[0]);
    }
    printWall.scrollTop = printWall.scrollHeight;
}

function encodeHTML(source) {
    return String(source)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function showMsg(message) {
    var text = message.text;
    var from = message.from;
    if (String(text).replace(/^\s+/, '').replace(/\s+$/, '')) {
        showMessage(encodeHTML(from), text);
    }
}

function showOnlineNum(){
    room.count().then(function (count) {
        onlineNum.innerHTML = count;
    });
}

function sendMsg() {

    // 如果没有连接过服务器
    if (firstFlag) {
        alert('请先连接服务器！');
        return;
    }
    if (clientId == '游客') {
        alert('请先登录！');
        return;
    }
    var val = inputSend.value;

    // 不让发送空字符
    if (String(val).replace(/^\s+/, '').replace(/\s+$/, '')) {
        //发送消息
        room.send(new AV.TextMessage(val)).then(function () {
            inputSend.value = '';
            showMessage(clientId, val, true);
        })
    } else {
        alert('请输入点文字！');
    }
}