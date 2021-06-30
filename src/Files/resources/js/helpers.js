
Window.prototype.checkResponse = function (response, onSuccess, noDialog, onFailur, onCatchError) {

    const RN_RESULT = 'result';
    const RES_SUCCESS = 'success';
    const ERR_ERR_MESSAGE = 'error_message';
    const RN_MESSAGE = 'message';

    try {
        if (typeof (response) != 'object') {
            response = response.trim();
            response = JSON.parse(response);
        }

        if (response['redirect']) {
            if (response['redirect'].indexOf("http") == 0 ||response['redirect'].indexOf('//') == 0 ) {
                if(response['redirect'].indexOf('//') == 0){
                    var url=response['redirect'].substring(1);
                }else{
                    var url=response['redirect'];
                }
                window.location.replace(url)
            } else {
                this.router.push(response['redirect']);
            }
        }

        if (response[RN_RESULT] == RES_SUCCESS) {


            if (onSuccess) {
                onSuccess.call(this, response);
            }
            if (!noDialog) {
                alert2(response[RN_MESSAGE], "عملیات موفق", 'success');
            }

        } else if (response[RN_RESULT] == ERR_ERR_MESSAGE) {
            if (onFailur) {
                onFailur.call(this, response);
            }
            alert2(response[RN_MESSAGE], "خطا", 'error');
        } else {
            alert2(response[RN_MESSAGE], "مشکلی رخ داد !", 'error');
            if (onFailur) {
                onFailur.call(this, response);
            }
        }
    } catch (e) {
        console.log(e);
        if (onCatchError) {
            onCatchError.call(this, response);
        }
        if (!noDialog) {
            alert2("مشکلی رخ داد!!", "خطای ارتباط!!!", 'error');
        }
    }


};
