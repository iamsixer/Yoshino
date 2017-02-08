/**
 * Created by Volio on 2017/2/8.
 */
var videoElement = document.getElementById("live-player")
var isIE11 = (/Trident\/7\./).test(navigator.userAgent)
var inputContent = document.getElementById("chat-textarea")
var chatSendBtn = document.getElementById("chat-send-btn")
var printWall = document.getElementById("printWall")
var printScroll = document.getElementById("printScroll")
var isConnected = false

var bindEvent = function (dom, eventName, fun) {
    if (window.addEventListener) {
        dom.addEventListener(eventName, fun);
    } else {
        dom.attachEvent('on' + eventName, fun);
    }
}

var encodeHtml = function (source) {
    return String(source)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
}

var showMessage = function (name, message, system) {
    var msg
    if (system) {
        msg = '<span class="system-message-name">' + encodeHtml(name) + ':&nbsp;</span><span class="system-message-content">' + encodeHtml(message) + '</span>'
    } else {
        msg = '<span class="chat-message-username">' + encodeHtml(name) + ':&nbsp;</span><span class="chat-message-content">' + encodeHtml(message) + '</span>'
    }
    var item = document.createElement("li")
    item.className = "chat-message-item"
    item.innerHTML = msg
    printWall.appendChild(item);
    if (printWall.childNodes.length > 150) {
        printWall.removeChild(printWall.childNodes[0]);
    }
    printScroll.scrollTop = printScroll.scrollHeight;
}

var showUserMsg = function (name, message) {
    if (String(message).replace(/^\s+/, '').replace(/\s+$/, '')){
        showMessage(name, message, false)
    }
}

var showSystemMsg = function (message) {
    showMessage("系统消息", message, true)
}

var sendMsg = function () {
    if (!isConnected) {
        showSystemMsg("请先连接服务器")
        return
    }

    var value = inputContent.value

    // 不让发送空字符
    if (String(value).replace(/^\s+/, '').replace(/\s+$/, '')) {
        //发送消息
        inputContent.value = ""
        danmaku.sendMessage(value)
    } else {
        alert('请输入点文字！');
    }
}

bindEvent(chatSendBtn, 'click', sendMsg);
bindEvent(document.body, 'keydown', function (e) {
    if (e.keyCode === 13) {
        sendMsg();
    }
});

var Player = function (config) {
    this.videoElement = config.element
    this.hlsUrl = config.hlsUrl
    this.init = function () {
        var video = this.videoElement
        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(this.hlsUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                video.play();
            });
        } else {
            console.log("浏览器不支持 hls.js")
        }
    }
}

var resetVideoHeight = function () {
    var width = videoElement.offsetWidth;
    videoElement.height = width * 0.5625;
};

(function () {
    axios.get("/api/channels/" + rid + "/playurl").then(function (response) {
        if (response.status == 200) {
            var data = response.data
            var player = new Player({
                "element": document.getElementById('live-player'),
                "hlsUrl": data.HLS
            })
            player.init()
        }
    }).catch(function (error) {
        console.log(error)
    })
    //针对IE11的兼容性写法
    if (isIE11) {
        resetVideoHeight()
        addEventListener('resize', resetVideoHeight);
    }
})();