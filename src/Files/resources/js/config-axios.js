
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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



window.axios.interceptors.request.use(function (config) {
    if (config.url.includes('?')) {
        config.url += '&ajax=true';
    }
    else {
        config.url += '?ajax=true';
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});




axios.interceptors.response.use(function (response) {
    if (hasKey(response.data, 'items')){
        // try {
        //     response.data['items'].forEach((item) => {
        //         convertDataTimeCols(item);
        //     });
        // } catch (error) {}
    }else if(hasKey(response.data, 'item')){
        // convertDataTimeCols(response.data.item);
    }

    return response;
}, function (error) {
    if (error.response) {
        if ((error.response.config.data !== undefined)) {
            try {
                var data = JSON.parse(error.response.config.data);
                if (data.noDialog==1) {
                    checkResponse(error.response.data, null, null, true);
                }else{
                    checkResponse(error.response.data);
                }
            }
            catch (err) {
                checkResponse(error.response.data);
            }
        } else {
            checkResponse(error.response.data);
        }
    }
    return error;
});

function hasKey(array, searchKey){
    for (var key in array) {
        if(key === searchKey){
            return true
        }
    }
    return false;
}
