/**
 * Created by Volio on 2017/2/7.
 */
var danmakuApiUrl = "wss://danmaku-api.lolihome.net/danmaku"

if (!window.WebSocket) {
    window.WebSocket = window.MozWebSocket
}

var Base64 = {
    characters: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    encode: function (string) {
        var characters = Base64.characters;
        var result = '';

        var i = 0;
        do {
            var a = string.charCodeAt(i++);
            var b = string.charCodeAt(i++);
            var c = string.charCodeAt(i++);

            a = a ? a : 0;
            b = b ? b : 0;
            c = c ? c : 0;

            var b1 = ( a >> 2 ) & 0x3F;
            var b2 = ( ( a & 0x3 ) << 4 ) | ( ( b >> 4 ) & 0xF );
            var b3 = ( ( b & 0xF ) << 2 ) | ( ( c >> 6 ) & 0x3 );
            var b4 = c & 0x3F;

            if (!b) {
                b3 = b4 = 64;
            } else if (!c) {
                b4 = 64;
            }

            result += Base64.characters.charAt(b1) + Base64.characters.charAt(b2) + Base64.characters.charAt(b3) + Base64.characters.charAt(b4);

        } while (i < string.length);

        return result;
    },

    decode: function (string) {
        var characters = Base64.characters;
        var result = '';

        var i = 0;
        do {
            var b1 = Base64.characters.indexOf(string.charAt(i++));
            var b2 = Base64.characters.indexOf(string.charAt(i++));
            var b3 = Base64.characters.indexOf(string.charAt(i++));
            var b4 = Base64.characters.indexOf(string.charAt(i++));

            var a = ( ( b1 & 0x3F ) << 2 ) | ( ( b2 >> 4 ) & 0x3 );
            var b = ( ( b2 & 0xF  ) << 4 ) | ( ( b3 >> 2 ) & 0xF );
            var c = ( ( b3 & 0x3  ) << 6 ) | ( b4 & 0x3F );

            result += String.fromCharCode(a) + (b ? String.fromCharCode(b) : '') + (c ? String.fromCharCode(c) : '');

        } while (i < string.length);

        return result;
    }
};

var Danmaku = function (config) {
    var _this = this
    this.q = JSON.stringify(config)
    this.number = 0
    this.onopen = function (event) {
    }
    this._onopen = function (event) {
        _this.onopen(event)
    }
    this.onmessage = function (event) {
    }
    this._onmessage = function (event) {
        var response = JSON.parse(event.data)
        _this.onmessage(response)
        if (response.errorCode == 2002) {
            _this.number = parseInt(response.data.onlineNumber)
            _this.onNumberChange(_this.number)
        }
    }
    this.onclose = function (event) {
    }
    this.onNumberChange = function (number) {
    }
    this.sendMessage = function (message) {
        _this.socket.send(message)
    }
    this.init = function () {
        _this.socket = new WebSocket(danmakuApiUrl + "?q=" + Base64.encode(this.q))
        _this.socket.onopen = this._onopen
        _this.socket.onmessage = this._onmessage
        _this.socket.onclose = this.onclose
        setInterval(_this.sendHeartbeat, 30000)
    }
    this.sendHeartbeat = function () {
        _this.socket.send("heartbeat")
    }
}