/**
 * Created by Volio on 2017/2/8.
 */
var videoElement = document.getElementById("live-player")
var isIE11 = (/Trident\/7\./).test(navigator.userAgent);

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