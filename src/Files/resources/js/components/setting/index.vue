<template>
<div>
<!-- settings -->
<div class="card">
    <div class="card-header " dir="rtl">
        <div class="card-title" >{{settingTitle}}</div>
        <ul v-if="settingType == 'general' " class="nav nav-tabs d-flex justify-content-end align-items-end">
            <li class="nav-item"><a @click="selectedTab= 'basic' " :class="['nav-link', selectedTab == 'basic'? 'text-primary' : 'text-secondary']" data-toggle="tab" href="#t1"> تنظیمات پایه</a></li>
            <li class="nav-item"><a @click="selectedTab= 'agreement' " :class="['nav-link', selectedTab == 'agreement'? 'text-primary' : 'text-secondary']" data-toggle="tab" href="#t2"> توافق نامه کاربران </a></li>
            <li class="nav-item"><a @click="selectedTab= 'privacy' " :class="['nav-link', selectedTab == 'privacy'? 'text-primary' : 'text-secondary']" data-toggle="tab" href="#t3"> حریم خصوصی </a></li>
            <li class="nav-item"><a @click="selectedTab= 'about' " :class="['nav-link', selectedTab == 'about'? 'text-primary' : 'text-secondary']" data-toggle="tab" href="#t5">معرفی برنامه</a></li>
            <li class="nav-item"><a @click="selectedTab= 'social' " :class="['nav-link', selectedTab == 'social'? 'text-primary' : 'text-secondary']" data-toggle="tab" href="#t6">راههای ارتباطی</a></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="row">



            <div class="col-sm-12">
                <form @submit.prevent="updateSettings()">

                    <div class="row" v-if="settingType == 'app-control' ">
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="row">
                                <div class="col-sm-5 text-left">
                                        <span v-if="under_maintenance_until == null || under_maintenance_until == '' " class="btn btn-primary cursor-pointer"
                                              data-toggle="modal" data-target="#maintenanceModal" style="min-width:200px">Maintenance Mode</span>
                                    <button v-else class="btn btn-success cursor-pointer " @click="activateMaintenanceMode('deactive')">Remove Maintenance Mode</button>
                                    <p class="mt-1"><b>وضعیت :</b>
                                        <span v-if="under_maintenance_until == null || under_maintenance_until == '' ">غیر فعال</span>
                                        <span  v-else>فعال
                                                <br>
                                                <span > اپ تا تاریخ  {{under_maintenance_until}} غیر فعال است.</span>
                                                <span>فعال سازی باید به صورت دستی انجام شود</span>
                                            </span>
                                    </p>
                                </div>
                                <div class="col-sm-7">
                                    <form-textarea placeholder="توضیحات" v-model="maintenance_comment"></form-textarea>
                                </div>
                            </div>


                        </div>


                        <!-- maintenance modal -->
                        <modal-component modalId="maintenanceModal" >
                            <div class="col-sm-12">
                                <form @submit.prevent="activateMaintenanceMode()">
                                    <date-picker v-model="maintenance_date" type="datetime"></date-picker>
                                    <br>
                                    <form-inputs type="submit" customClass="btn btn-outline-primary" val="ثبت"></form-inputs>
                                </form>
                            </div>
                        </modal-component>
                        <!-- / -->
                    </div>


                    <!-- --------------------------------------------------------------------------------------------------------------- -->
                    <!-- --------------------------------------------------------------------------------------------------------------- -->
                        <div class="row mt-4" v-if="settingType == 'app-version' ">
                            <!-- android app version -->
                            <div class="col-sm-6">
                                <form-inputs type="number" title="حداقل ورژن اندروید" v-model="minimumAppVersionAndroid"></form-inputs>
                                <form-inputs type="number" title="آخرین ورژن اندروید" v-model="latestAppVersionAndroid"></form-inputs>
                            </div>
                            <!-- / -->


                            <!-- pwa app version -->
                            <div class="col-sm-6">
                                <form-inputs type="number" title="حداقل ورژن PWA" v-model="minimumAppVersionPWA"></form-inputs>
                                <form-inputs  type="number" title="آخرین ورژن PWA" v-model="latestAppVersionPWA"></form-inputs>
                            </div>
                            <!-- / -->
                        </div>
                        <!-- --------------------------------------------------------------------------------------------------------------- -->
                        <!-- --------------------------------------------------------------------------------------------------------------- -->
                        <div class="row mt-4" v-if="settingType == 'bank-limit' ">
                            <div class="col-sm-6">
                                <form-inputs type="number" title="مقدار اولیه خرید" v-model="initial_price"></form-inputs>
                                <form-inputs type="number" title="سقف مبلغ خرید ( ریال )" v-model="max_price"></form-inputs>
                            </div>
                            <div class="col-sm-6">
                                <form-inputs type="number" title="پله های افزایش ریال" v-model="price_steps"></form-inputs>
                            </div>
                        </div>
                        <!-- --------------------------------------------------------------------------------------------------------------- -->
                        <!-- --------------------------------------------------------------------------------------------------------------- -->
                        <div v-if="settingType == 'general' ">
                            <div class="tab-content">
                                <div class="tab-pane container active" id="t1">
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <form-inputs title="متن" v-model="var1"></form-inputs>
                                        </div>
                                        <div class="col-sm-6">
                                            <form-inputs title="متن2" v-model="var2"></form-inputs>
                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane container fade" id="t2">
                                    <div class="row mt-4"  >
                                        <div class="col-sm-12">
                                            <form-ckeditor title='توافق نامه کاربران' v-model='signupRules'></form-ckeditor>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane container fade" id="t3">
                                    <div class="row mt-4"  >
                                        <div class="col-sm-12">
                                            <form-ckeditor title='قوانین حریم خصوصی' v-model='privacy'></form-ckeditor>
                                        </div>
                                    </div>
                                </div>




                                <div class="tab-pane container fade" id="t5">
                                    <div class="row mt-4" >
                                        <div class="col-sm-12">
                                            <form-ckeditor title='معرفی' v-model='about'></form-ckeditor>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane container fade" id="t6">
                                    <div class="row mt-4" >
                                        <div class="col-sm-6">
                                            <form-inputs title="تلفن تماس" v-model="contact_phone" icon="fa fa-phone"></form-inputs>
                                            <form-inputs title="شماره واتس اپ" v-model="contact_whatsapp" icon="fa fa-whatsapp"></form-inputs>
                                        </div>
                                        <div class="col-sm-6">
                                            <form-inputs title="اکانت تلگرام" v-model="contact_telegram" icon="fa fa-send"></form-inputs>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- --------------------------------------------------------------------------------------------------------------- -->



                    <form-inputs   type="submit" class="mx-auto w-50 mt-3" customClass="btn btn-outline-primary" val="ذخیره"></form-inputs>
                </form>
            </div>








        </div>
    </div>
</div>
<!-- / -->






</div>
</template>

<script>

    export default {

        data(){
            return {
                setting: {},

                minimumAppVersionAndroid:'',
                latestAppVersionAndroid:'',
                minimumAppVersionPWA:'',
                latestAppVersionPWA:'',
                signupRules:'',
                privacy:'',
                about:'',
                initial_price:'',
                max_price:'',
                price_steps:'',
                contact_phone:'',
                contact_telegram:'',
                contact_whatsapp:'',
                under_maintenance_until: '1399/01/01',
                maintenance_operation: '',
                maintenance_date: '',
                files: [],
                has_root_permission: [],

                settingType : '',
                settingTitle : 'تنظیمات',
                exchangeRates : [],
                selectedIds : [],
                sort : '',
                sortType : 'desc',
                selectedTab : 'basic',
                maintenance_comment : null,
                app_mode_comment : null,
            }
        },
        methods: {
            fetchData(){
                axios.get('/setting')
                .then(response => {
                    this.minimumAppVersionAndroid = response.data.settings.minimumAppVersionAndroid;
                    this.latestAppVersionAndroid = response.data.settings.latestAppVersionAndroid;
                    this.minimumAppVersionPWA = response.data.settings.minimumAppVersionPWA;
                    this.latestAppVersionPWA = response.data.settings.latestAppVersionPWA;
                    this.signupRules = response.data.settings.signup_rules;
                    this.privacy = response.data.settings.privacy;
                    this.about = response.data.settings.about;
                    this.initial_price = response.data.settings.initial_price;
                    this.max_price = response.data.settings.max_price;
                    this.price_steps = response.data.settings.price_steps;
                    this.contact_phone= response.data.settings.contact_phone;
                    this.contact_telegram= response.data.settings.contact_telegram;
                    this.contact_whatsapp= response.data.settings.contact_whatsapp;
                    this.maintenance_comment = response.data.settings.maintenance_comment;

                    // amdin roles
                    this.has_root_permission = response.data.has_root_permission;

                })
            },



            updateSettings(){

                var formData = new FormData();
                formData.append('minimumAppVersionAndroid', this.minimumAppVersionAndroid );
                formData.append('latestAppVersionAndroid', this.latestAppVersionAndroid );
                formData.append('minimumAppVersionPWA', this.minimumAppVersionPWA );
                formData.append('latestAppVersionPWA', this.latestAppVersionPWA );
                formData.append('signup_rules', this.signupRules );
                formData.append('privacy', this.privacy );
                formData.append('about', this.about );
                formData.append('initial_price', this.initial_price );
                formData.append('max_price', this.max_price );
                formData.append('price_steps', this.price_steps );
                formData.append('contact_phone', this.contact_phone);
                formData.append('contact_telegram', this.contact_telegram);
                formData.append('contact_whatsapp', this.contact_whatsapp);
                formData.append('maintenance_comment', this.maintenance_comment );


                formData.append('setting_type', this.settingType);

                axios.post('/setting/update',formData,{
                     headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    checkResponse(response.data);
                })

            },


            activateMaintenanceMode(op = null){
                if(op == null){
                    this.maintenance_operation = 'activate';
                }
                else{
                    this.maintenance_operation = 'deactive';
                }
                axios.post('/maintenance',{
                    under_maintenance_until : this.maintenance_date,
                    operation : this.maintenance_operation,
                })
                .then(response=>{
                    checkResponse(response.data,()=>{
                        window.location.reload();
                    });
                })
            },


            setSettingTitle(){
                switch (this.settingType) {
                    case "bank-limit":
                        this.settingTitle = 'محدودیت های حساب بانکی'
                        break;
                    case "app-version":
                        this.settingTitle = 'نسخه های اپلیکیشن'
                        break;
                    case "app-control":
                        this.settingTitle = 'کنترل اپلیکیشن'
                        break;
                    case "general":
                        this.settingTitle = 'تنظیمات کلی'
                        break;
                    default:
                        this.settingTitle = 'تنظیمات'
                        break;
                }
            },

        },
        mounted() {
            this.$route.params.type === undefined ? this.settingType = 'general' : this.settingType = this.$route.params.type;
            this.setSettingTitle()
            this.fetchData();
        },
        watch: {
            '$route.params.type': {
                immediate:true,
                handler (val, oldVal) {
                    this.settingType = val;
                },
            },
        }

    }
</script>
