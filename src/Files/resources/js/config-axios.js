
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// CSRF & XCSRF

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


axios.interceptors.request.use(request => {

    var extras={
        reqTime:(new Date()).getTime(),
        ajax:'true',
    };


    if(request.params!==undefined){
        request.params = {... request.params, ...extras};
    }else if(request.data!==undefined ){
        if(request.data instanceof FormData){ // is a formData
            for (const property in extras) {
                request.data.append(property,extras[property])
            }
        }else{ // is an object
            request.data = {... request.data, ...extras};
        }
    }else{
        if(request.method === 'get' ||  request.method === 'delete'){
            request.params = { ...extras};
        }else{
            request.data = {...extras};
        }
    }

    return request;
}, error => {
    return Promise.reject(error);
});


axios.interceptors.response.use(response => {
    swal.close();
    return response;
}, error => {
    if(error.response)
        checkResponse(error.response.data);

    swal.close();

    return Promise.reject(error);
});
