<template>
    <div  id="data-list-view" class="data-list-view-header">
        <section class="page-users-view">
            <div class="row">



                <!-- admin info -->
                <div class="col-sm-6">
                    <card-component title="پروفایل" customClass="h-100">
                        <div class="col-sm-3">
                            <img class="img-fluid max-height-70 rounded-circle" :src="admin.image">
                            <button type="button" class="btn btn-primary btn-sm" @click="uploadImage()">ویرایش</button>
                        </div>
                        <div class="col-sm-9 p-1">
                            <form-label title="شناسه مدیریت" :val="admin.id"></form-label>
                            <form-label title="نام " :val="admin.name"></form-label>
                            <form-label title="نام کاربری " :val="admin.username"></form-label>
                            <form-label title="شماره تماس " :val="admin.phonenumber"></form-label>
                            <router-link  class=" btn btn-primary d-block waves-effect w-50 mx-auto waves-light d-block mt-2" :to="'/admin/create/'+admin.id">
                                <i class="feather icon-edit-1"></i>&nbsp; ویرایش
                            </router-link>
                        </div>
                    </card-component>
                </div>
                <!-- / -->
             <!-- admin signature -->
                <div class="col-sm-6" v-if="isAdmin==1">
                    <card-component title="امضای مدیر" customClass="h-100">
                        <div class="col-sm-3">
                            <img class="img-fluid max-height-70 rounded-circle" :src="admin.signature">
                            <button type="button" class="btn btn-primary btn-sm" @click="uploadSignature()">ویرایش</button>

                        </div>
                    </card-component>
                </div>
                <!-- / -->


                <!-- admin change password -->
                <div class="col-sm-6" >
                    <card-component title="ویرایش رمز عبور" customClass="h-100">
                        <div class="col-sm-12 p-1">
                            <form class="form-horizontal" @submit.prevent="changePass()">
                                <div class="box-body">
                                    <form-inputs type="password" title="رمز عبور فعلی" v-model="oldPassword"></form-inputs>
                                    <form-inputs type="password"  title="رمز عبور جدید" v-model="newPassword"></form-inputs>
                                    <div :class="repeatClass">
                                        <form-inputs type="password" title="تکرار رمز عبور جدید" v-model="newPasswordRepeat" :inputHint="repeatErrorText"></form-inputs>
                                        <!-- <span class="help-block text-danger" v-text=""></span> -->
                                    </div>
                                </div>
                                <form-inputs type="submit" customClass="btn btn-outline-primary" val="ویرایش"></form-inputs>
                            </form>
                        </div>
                    </card-component>
                </div>
                <!-- / -->



                <sidebar-form title="مدیر">
                    <admin-form :id="admin.id"></admin-form>
                </sidebar-form>






            </div>
        </section>
    </div>
</template>

<script>
    export default {

        data(){
            return {
                isAdmin: 0,
                admin: {},
                oldPassword: '',
                newPassword: '',
                newPasswordRepeat: '',
                repeatClass: '',
                repeatErrorText: '',
                word: {},
            }
        },
        methods: {
            uploadImage(){
                uploadFileDialog("عکس خود را انتخاب کنید",'/admin/uploadProfileImage',(response)=>{
                    checkResponse(response.data, () => {
                        let image = response.data.image;
                        this.admin.image = image;
                        $('[name="image-admin"]').attr('src', image);
                        $('#headerProfileImage').attr('src', image);
                    });
                });
            },

             uploadSignature(){
                uploadFileDialog("امضای را انتخاب کنید",'/admin/uploadSignatureImage',(response)=>{
                    checkResponse(response.data, () => {
                        this.admin.signature = response.data.image;
                    });
                });
            },


            changePass(){
                if (this.newPassword != this.newPasswordRepeat) {
                    alert2("رمز عبور های جدید همخوانی ندارند.", "", 'خطا');
                    return;
                }
                axios.post('/admin/changePassword', {
                    'old_password': this.oldPassword,
                    'new_password': this.newPassword
                })
                    .then(response => {
                        checkResponse(response.data,()=>{
                            this.oldPassword= '';
                            this.newPassword= '';
                            this.newPasswordRepeat= '';
                        })
                    })
            },

            checkRepeat(){
                if (this.newPassword != this.newPasswordRepeat) {
                    this.repeatClass = 'text-danger';
                    this.repeatErrorText='رمز عبور های جدید همخوانی ندارند!';
                } else {
                    this.repeatClass = '';
                    this.repeatErrorText='';
                }
            },


        },

        watch: {
            newPassword(){
                this.checkRepeat();
            },
            newPasswordRepeat(){
                this.checkRepeat();
            }
        },


        mounted() {
            axios.get('/admin/profile')
            .then(response => {
                this.admin = response.data.item;
                this.isAdmin = response.data.is_admin;
            })
        }
    }
</script>
