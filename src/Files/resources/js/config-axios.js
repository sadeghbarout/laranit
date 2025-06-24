import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// CSRF & XCSRF

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


axios.interceptors.request.use(request => {

    // put Params in URL  **********
    const requestParams = request.params
    const url = new URL(window.location.href);
    // console.log("AXIOS")
    // console.log(requestParams)
    if(requestParams !== undefined && Object.keys(requestParams).length != 0){
        for (var [key, value] of Object.entries(requestParams)) {
            url.searchParams.set(key, value);
        }
    }
    window.history.replaceState(null, null, url);
    //**********

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

// interceptors
// window.axios.interceptors.request.use(function (config) {
//     if (config.url.includes('?')) {
//         config.url += '&ajax=true';
//     }
//     else {
//         config.url += '?ajax=true';
//     }
//     return config;
// }, function (error) {
//     return Promise.reject(error);
// });


axios.interceptors.response.use(response => {
    hideLoading();
    return response;
}, error => {
    if(error.response)
        checkResponse(error.response.data);

    hideLoading();

    return Promise.reject(error);
});
