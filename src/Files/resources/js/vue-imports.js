window.Vue = require('vue');
Vue.config.devtools = true;


// window.VueRouter = require('vue-router').default;
import VueRouter from 'vue-router';
Vue.use(VueRouter);
window.router = require('./routes').default;


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
Vue.use(VuePersianDatetimePicker, {
    name: 'custom-date-picker',
    props: {
        inputFormat: 'YYYY/MM/DD HH:mm:ss',
        format: 'jYYYY/jMM/jDD HH:mm:ss',
        editable: false,
        inputClass: 'form-control my-custom-class-name',
        placeholder: 'تاریخ مورد نظر خود را انتخاب کنید',
        altFormat: 'YYYY/MM/DD HH:mm:ss',
        color: '#00acc1',
        autoSubmit: false,
        timePicker : true,
        //...
        //... And whatever you want to set as default
        //...
    }
});
Vue.component('form-inputs', require('./components/form-inputs.vue'));
Vue.component('form-date', require('./components/form-date.vue'));
Vue.component('form-label', require('./components/form-label.vue'));
Vue.component('form-textarea', require('./components/form-textarea.vue'));
Vue.component('form-uploader', require('./components/form-uploader.vue'));
Vue.component('form-select', require('./components/form-select.vue'));
Vue.component('form-page-rows', require('./components/form-page-rows.vue'));
Vue.component('pagination', require('./components/pagination.vue'));
Vue.component('card-component', require('./components/card-component.vue'));
Vue.component('modal-component', require('./components/modal-component.vue'));
Vue.component('image-slider-component', require('./components/image-slider-component.vue'));
Vue.component('th-sort', require('./components/th-sort.vue'));
Vue.component('date-picker', VuePersianDatetimePicker);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (typeof (vue) === 'undefined') {
    window.vue = {};
}
vue['el'] = '#vueAppDiv';
vue['router'] = router;
window.appVue = new Vue(vue);