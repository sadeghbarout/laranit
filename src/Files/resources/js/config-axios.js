
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// CSRF & XCSRF

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


// interceptors
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


axios.interceptors.response.use(response => {
    return response;
}, error => {
    if(error.response)
        checkResponse(error.response.data);

    return Promise.reject(error);
});
