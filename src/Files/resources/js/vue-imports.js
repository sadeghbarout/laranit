import {createApp} from 'vue'
import router from './routes'
import App from './components/app.vue'
import userStore from './stores/user';

window.router=router
if (typeof (vue) === 'undefined') {
    window.vue = {};
}

const app = createApp(App);
app.use(router)

// stores
app.config.globalProperties.$user = userStore


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


import formInputs from './components/form-inputs.vue';
import formInputs2 from './components/form-inputs2.vue';
import formSwich from './components/form-swich.vue';
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
import checkTd from './components/check-td.vue';
import filterCard from './components/filter-card.vue';
import slideDown from './components/slide-down.vue';
import formQuill from './components/form-quill.vue';
import multiselect from './components/multiselect.vue';

import appFooter from './layout/appFooter.vue';
import appHeader from './layout/appHeader.vue';
import appSidebar from './layout/appSidebar.vue';


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
app.component('check-td', checkTd)
app.component('filter-card', filterCard);
app.component('slide-down', slideDown);
app.component('form-quill', formQuill);
app.component('form-swich', formSwich);
app.component('form-inputs2', formInputs2);
app.component('multiselect', multiselect);

// app.component('date-picker', VuePersianDatetimePicker);

app.component('appFooter', appFooter);
app.component('appHeader', appHeader);
app.component('appSidebar', appSidebar);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 app.mixin({
    data() {
        return {
            PERM_ROOT: 'root',

            PERM_ROLE_LIST_SHOW: 'PERM_ROLE_LIST_SHOW',
            PERM_ROLE_STORE: 'PERM_ROLE_STORE',
            PERM_ROLE_UPDATE: 'PERM_ROLE_UPDATE',
            PERM_ROLE_DESTROY: 'PERM_ROLE_DESTROY',
            PERM_ROLE_PERMISSION: 'PERM_ROLE_PERMISSION',

            PERM_ADMIN_LIST_SHOW: 'PERM_ADMIN_LIST_SHOW',
            PERM_ADMIN_STORE: 'PERM_ADMIN_STORE',
            PERM_ADMIN_UPDATE: 'PERM_ADMIN_UPDATE',
            PERM_ADMIN_DESTROY: 'PERM_ADMIN_DESTROY',
            PERM_ADMIN_ROLE: 'PERM_ADMIN_ROLE',
        }
    },
    methods: {
        adminHasPermission: function (requiredPermission) {
            const adminPermissionsArrayObj =  userStore.permissions

            const adminPermissionsArray = Object.keys(adminPermissionsArrayObj).map((key) => adminPermissionsArrayObj[key] );

            if (adminPermissionsArray.indexOf('root') != -1)
                return true;

            return adminPermissionsArrayObj.indexOf(requiredPermission) != -1
        }
    }
});




app.mount('#vueAppDiv');
app.config.devtools = true;

