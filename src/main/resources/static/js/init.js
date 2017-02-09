/**
 * Created by Volio on 2017/2/7.
 */
var navInit = function (data) {
    document.getElementById("nav-avatar").src = data.avatar + "?s=120"
    document.getElementById("nav-mail").innerHTML = data.email
}

var danmakuInit = function (data) {
    if (typeof Danmaku != "undefined") {
        showSystemMsg("正在连接服务器...")
        if (data != null)
            window.danmaku = new Danmaku({
                "id": data.id,
                "rid": rid,
                "username": data.username,
                "token": "null"
            })
        else
            window.danmaku = new Danmaku({
                "id": 0,
                "rid": rid,
                "username": "Visitor",
                "token": "null"
            })

        danmaku.onopen = function (event) {
            isConnected = true
            showSystemMsg("连接服务器成功...")
        }

        danmaku.onmessage = function (data) {
            if (data.errorCode == 0) {
                var message = data.data
                showUserMsg(message.username, message.message)
            }
            if (data.errorCode == 1001) {
                showSystemMsg(data.errorMsg)
            }
        }

        danmaku.onNumberChange = function (number) {
            updateOnlineNumber(number)
        }

        danmaku.onclose = function (event) {
            isConnected = false
            showSystemMsg("服务器连接已断开")
        }

        danmaku.init()
    }
}

window.onload = function () {
    if (typeof playerInit != "undefined") {
        playerInit()
    }
};

(function () {
    axios.get("/api/user").then(function (response) {
        navInit(response.data)
        danmakuInit(response.data)
    }).catch(function () {
        danmakuInit()
    })
})();