import {createApp} from 'vue'
import router from './routes'
import SecureLs from 'secure-ls'

Window.prototype.preferences = new SecureLs()
if (typeof (vue) === 'undefined') {
    window.vue = {};
}

const app = createApp(vue);
app.use(router)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

 import Vue3PersianDatetimePicker from 'vue3-persian-datetime-picker'
 app.use(Vue3PersianDatetimePicker, {
     name: 'date-picker',
     props: {
         format: 'YYYY-MM-DD HH:mm:ss',
         inputFormat: 'YYYY-MM-DD HH:mm:ss',
         displayFormat: 'jYYYY/jMM/jDD HH:mm:ss',
         altFormat: 'YYYY-MM-DD HH:mm:ss',

         editable: false,
         inputClass: 'form-control my-custom-class-name',
         placeholder: 'انتخاب تاریخ',
         color: '#00acc1',
         autoSubmit: false,
     }
 })


import adminLogin from './components/adminLogin.vue';
import formInputs from './components/form-inputs.vue';
import formDate from './components/form-date.vue';
import formLabel from './components/form-label.vue';
import formTextarea from './components/form-textarea.vue';
import formUploader from './components/form-uploader.vue';
import formSelect from './components/form-select.vue';
import formPageRows from './components/form-page-rows.vue';
import pagination from './components/pagination.vue';
import cardComponent from './components/card-component.vue';
import modelComponent from './components/modal-component.vue';
import imageSliderComponent from './components/image-slider-component.vue';
import thSort from './components/th-sort.vue';

app.component('admin-login', adminLogin)
app.component('form-inputs', formInputs)
app.component('form-date', formDate)
app.component('form-label', formLabel)
app.component('form-textarea', formTextarea)
app.component('form-uploader', formUploader)
app.component('form-select', formSelect)
app.component('form-page-rows', formPageRows)
app.component('pagination', pagination)
app.component('card-component', cardComponent)
app.component('modal-component', modelComponent)
app.component('image-slider-component', imageSliderComponent)
app.component('th-sort', thSort)
// app.component('date-picker', VuePersianDatetimePicker);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 app.mixin({
    data() {
        return {
            PERM_ROOT : 'root',
        }
    },
    methods: {
        adminHasPermission: function (requiredPermission) {
            var adminPermissionsArrayObj =  preferences.get('admin_permissions')
            var adminPermissionsArray = Object.keys(adminPermissionsArrayObj).map((key) => adminPermissionsArrayObj[key] );

            if (adminPermissionsArray.indexOf('root') != -1)
                return true;

            return adminPermissionsArrayObj.indexOf(requiredPermission) != -1
        }
    }
});




app.mount('#vueAppDiv');
app.config.devtools = true;

