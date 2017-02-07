/**
 * Created by Volio on 2017/2/7.
 */
var navInit = function (data) {
    document.getElementById("nav-avatar").src = data.avatar + "?s=120"
    document.getElementById("nav-mail").innerHTML = data.email
}

var danmakuInit = function (data) {
    if (Danmaku) {
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
            console.log("连接已建立")
        }

        danmaku.onmessage = function (event) {
            console.log(event.data)
        }

        danmaku.onclose = function (event) {
            console.log("连接已关闭")
        }

        danmaku.init()
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

