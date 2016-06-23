/**
 * Created by Volio on 2016/6/1.
 */
if (document.getElementById('setting_room_desc_btn')) {
    setting_room_desc_btn.onclick = function () {
        $token = getToken();
        $.post('/api/user/setting/update',
            'room_desc=' + document.getElementById('setting_room_desc').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    };

    setting_room_name_btn.onclick = function () {
        $token = getToken();
        $.post('/api/user/setting/update',
            'room_name=' + document.getElementById('setting_room_name').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    };

    $("#long_desc_area").blur(function () {
        $token = getToken();
        $.post('/api/user/setting/update',
            'long_desc=' + document.getElementById('long_desc_area').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    });

    $("#category_id").blur(function () {
        $token = getToken();
        $.post('/api/user/setting/update',
            'category_id=' + document.getElementById('category_id').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    });
}

if (document.getElementById('setting_user_name_btn')) {
    setting_user_name_btn.onclick = function () {
        $token = getToken();
        $.post('/api/user/info/update',
            'user_name=' + document.getElementById('setting_user_name').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    };

    setting_user_email_btn.onclick = function () {
        $token = getToken();
        $.post('/api/user/info/update',
            'user_email=' + document.getElementById('setting_user_email').value + '&_token=' + $token,
            function (data) {
                if (data.code == 200) {
                    saveSuccess();
                }
            }
        );
    };
}

function saveSuccess() {
    noticeShow();
    setTimeout(noticeHide, 3000)
}

function noticeShow() {
    $("#setting-notice").fadeIn();
}

function noticeHide() {
    $("#setting-notice").fadeOut()
}