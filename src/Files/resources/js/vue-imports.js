import {createApp} from 'vue'
import router from './routes'
import App from './app.vue'
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


import formInputs from './components/custom/form/form-inputs.vue';
import formInputs2 from './components/custom/form/form-inputs2.vue';
import formSwich from './components/custom/form/form-swich.vue';
import formDate from './components/custom/form/form-date.vue';
import formLabel from './components/custom/form/form-label.vue';
import formTextarea from './components/custom/form/form-textarea.vue';
import formUploader from './components/custom/form/form-uploader.vue';
import formSelect from './components/custom/form/form-select.vue';
import formPageRows from './components/custom/form/form-page-rows.vue';
import pagination from './components/custom/app/pagination.vue';
import cardComponent from './components/custom/app/card-component.vue';
import modelComponent from './components/custom/app/modal-component.vue';
import imageSliderComponent from './components/custom/app/image-slider-component.vue';
import checkTd from './components/custom/table/check-td.vue';
import filterCard from './components/custom/app/filter-card.vue';
import slideDown from './components/custom/app/slide-down.vue';
import formQuill from './components/custom/form/form-quill.vue';
import formSelect2 from './components/custom/form/form-select2.vue';
import multiselect from './components/custom/form/multiselect.vue';
import modal from './components/custom/app/modal.vue';
import tab from './components/custom/app/tab.vue';

import btnIcon from './components/custom/button/btn-icon.vue';
import excelExportButton from './components/custom/button/excel-export-button.vue';


import customTh from './components/custom/table/custom-th.vue';
import customColumnTh from './components/custom/table/custom-column-th.vue';
import customColumnTd from './components/custom/table/custom-column-td.vue';
import customColumnDialog from './components/custom/table/custom-column-dialog.vue';
import customTable from './components/custom/table/custom-table.vue';
import tdLabel from './components/custom/table/td-label.vue';
import thSort from './components/custom/table/th-sort.vue';

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
app.component('form-select2', formSelect2);
app.component('form-swich', formSwich);
app.component('form-inputs2', formInputs2);
app.component('multiselect', multiselect);
app.component('modal', modal);
app.component('btn-icon', btnIcon);
app.component('excel-export-button', excelExportButton)


app.component('custom-th', customTh);
app.component('custom-column-th', customColumnTh);
app.component('custom-column-td', customColumnTd);
app.component('custom-column-dialog', customColumnDialog);
app.component('custom-table', customTable);
app.component('td-label', tdLabel);
app.component('tab', tab);

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

            PERM_LOGS_LIST_SHOW: 'PERM_LOGS_LIST_SHOW',
            PERM_LOGS_EXCEL: 'PERM_LOGS_EXCEL',
            PERM_LOGS_LIST_UPDATE: 'PERM_LOGS_LIST_UPDATE',
        }
    },
    methods: {
        adminHasPermission: function (requiredPermission) {
            const adminPermissionsArrayObj =  userStore.permissions

            const adminPermissionsArray = Object.keys(adminPermissionsArrayObj).map((key) => adminPermissionsArrayObj[key] );

            if (adminPermissionsArray.indexOf('root') != -1)
                return true;

            return adminPermissionsArrayObj.indexOf(requiredPermission) != -1
        },
        priceFormat(price, withUnit = true){
            try {
                if(!isNaN(price)){
                    return parseInt(price).toLocaleString('en-US') + (withUnit? ' ریال ' : '');
                }
            }catch (e) {}

            return '';
        },
        shortenIP(ip) {
            if (ip!=null && ip.includes(':')) {
                let parts = ip.split(':');
                if (parts.length > 2) {
                    return `${parts[0]}:${parts[1]}:...:${parts[parts.length - 2]}:${parts[parts.length - 1]}`;
                }
            }
            return ip;
        },
        truncateText(text, maxLength) {
            if (text.length > maxLength) {
                return text.slice(0, maxLength) + "...";
            }
            return text;
        },
        copyTextToClipboard(text) {
            if (!navigator.clipboard) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.opacity = '0';
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand('copy');
                    alert2('متن با موفقیت در حافظه کپی شد.');
                } catch (err) {
                    alert2('خطا در کپی کردن متن.',null ,'error');
                }
                document.body.removeChild(textarea);
            } else {
                navigator.clipboard.writeText(text).then(() => {
                    alert2('متن با موفقیت در حافظه کپی شد.');
                }).catch(err => {
                    alert2('خطا در کپی کردن متن.',null ,'error');
                });
            }
        },
        chunkArray(arr, size) {
            let result = [];
            for (let i = 0; i < arr.length; i += size) {
                result.push(arr.slice(i, i + size));
            }
            return result;
        }
    }
});




app.mount('#vueAppDiv');
app.config.devtools = true;

