/**
 * Created by Volio on 2017/1/9.
 */
var notification = document.querySelector('.mdl-js-snackbar')

var notice = function (message, timeout) {
    var data = {
        message: message,
        timeout: timeout
    }
    notification.MaterialSnackbar.showSnackbar(data)
};

(function () {
    axios.get("/api/user").then(function (response) {
        if (response.status == 200) {
            var data = response.data
            document.getElementById("nav-avatar").src = data.avatar + "?s=120"
            document.getElementById("nav-mail").innerHTML = data.email
        }
    }).catch(function (error) {
        console.log(error)
    })
})();