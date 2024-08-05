import swal from 'sweetalert2'
window.swal = swal;


Window.prototype.alert2 = function (text, title, type, onConfirm) {
    if (!text) {
        text = ""
    }
    if (!title) {
        title = ""
    }
    if (!type) {
        type = "success"
    }
    // swal(title, text, type, {
    //     button: "تایید",
    // });

    if (type == 'success') {
        toastr.success(text, title, { "progressBar": true });
    }
    else {
        toastr.error(text, title, { "progressBar": true });
    }

    // new swal({
    //     title: title,
    //     html: text,
    //     type: type,
    //     confirmButtonColor: '#50ae2f',
    //     confirmButtonText: 'تایید',
    // }).then((res) => {
    //     if (res.value && onConfirm) {
    //         onConfirm.call(this);
    //     }
    // })

};




//----------------------------------------------------------------------------------------------------------------
Window.prototype.confirm2 = function (message, title, onSuccess, type) {

    if (!type) {
        type = 'warning';
    }
    new swal({
        title: title,
        text: message,
        type: type,
        showCancelButton: true,
        confirmButtonColor: '#7367F0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    }).then((result) => {
        if (result.value) {
            onSuccess.call(this);
        }
    })

};




// ---------------------------------------------------------------------------------------------------------------
Window.prototype.prompt2 = function (message, value, onSuccess, dateInput = false, text = null) {
    new swal({
        title: message,
        input: 'text',
        inputValue: value,
        text: text,
        inputAttributes: {
            autocapitalize: 'off',
        },
        showCancelButton: true,
        confirmButtonColor: '#7367F0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'تایید',
        cancelButtonText: 'لغو',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !swal.isLoading()
    }).then((result) => {
        if (result.value) {
            onSuccess.call(this, result.value)
        }
    })
};



//---------------------------------------------------------------------------------------------------------------
Window.prototype.uploadFileDialog = function (title, url, onSuccess, type) {

    if (!type) {
        type = 'image/*';
    }

    new swal({
        title: title,
        input: 'file',
        inputAttributes: {
            'accept': type,
        },
        confirmButtonColor: '#7367F0',
        confirmButtonText: 'ارسال',
    }).then((res) => {
        if (res.value) {

            showLoadingUploadFile();
            var formData = new FormData();
            formData.append("file", res.value);
            axios.post(url,
                formData,
                {
                    headers: {'Content-Type': 'multipart/form-data',},
                    onUploadProgress: onUploadProgressAxios
                }
            ).then(response => {
                swal.close();
                if (onSuccess)
                    onSuccess.call(this, response)
            })
        }

    });
};



//-------------------------------------------------------------------------------------------------------------
Window.prototype.showLoading = function () {
    new swal({
        title: 'در حال ارسال ...',
        html: 'لطفا صبر کنید ...',
        onOpen: () => {
            swal.showLoading()
        },
        confirmButtonColor: '#7367F0',

    });
};




//-------------------------------------------------------------------------------------------------------------
Window.prototype.showLoadingUploadFile = function () {
    new swal({
        title: 'درحال آپلود ...',
        html: '<strong></strong> %',
        onOpen: () => {
            swal.showLoading()
        },
        confirmButtonColor: '#7367F0',

    });
};



// --------------------------------------------------------------------------------------------------------------
Window.prototype.onUploadProgressAxios = function (progressEvent) {
    var percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
    swal.getContent().querySelector('strong').textContent = percentCompleted
};

