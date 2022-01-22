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




app.mount('#vueAppDiv');
app.config.devtools = true;

