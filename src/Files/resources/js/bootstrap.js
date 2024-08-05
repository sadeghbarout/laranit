import _ from 'lodash';
import * as popper from 'popper.js';
import jquery from 'jquery';

window._ = _;

window.Popper = popper;


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = jquery;

    // import('bootstrap');
} catch (e) {}



/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
//
// import Echo from "laravel-echo"
// window.Pusher = require('pusher-js');
// Window.prototype.connectWebsocket = function () {
//     window.Echo = new Echo({
//         broadcaster: 'pusher',
//         key: 'xxxxxxxxx',
//         wsHost: "api.xxxxxxx.ir",
//         wssHost: "api.xxxxxxx.ir",
//         // wsHost: window.location.hostname,
//         wsPort: 6001,
//         wssPort: 6001,
//         forceTLS: false,
//         disableStats: true,
//     });
//
// };



import './config-axios';
