<template>
    <div class="row">
        <div class="col-md-6 mx-auto">


            <card-component title="  مشخصات کارشناس  ">
                <div class="col-12">
                    <img class='img-fluid' :src='item.image' style="width: 100px">

                    <form-label title='شناسه' :val='item.id'></form-label>
                    <form-label title='نام' :val='item.name'></form-label>
                    <form-label title='موبایل' :val='item.phonenumber'></form-label>
                    <form-label title='نام کاربری' :val='item.username'></form-label>
                    <form-label title='وضعیت' :val='item.status_text' :classes="`badge badge-${item.status_color}`" id="status" :item-id="item.id" :options-val="item.status" :options="statuses"></form-label>
                    <hr>
                    <form-label title='آخرین IP' :val='item.ip'></form-label>
                    <form-label title='آخرین ورود' :val='item.last_login_fa'></form-label>
                    <form-label title='آخرین دستگاه' :val='item.device_info'></form-label>
                    <hr>
                    <form-label title='تاریخ ثبت' :val='item.created_at_fa'></form-label>
                    <form-label title='تاریخ آخرین ویرایش' :val='item.updated_at_fa'></form-label>


                    <br>
                    <div class="d-flex" style="gap: 6px;">
                        <router-link v-if="adminHasPermission(PERM_ADMIN_UPDATE)" :to="'/admin/create/'+item.id" class="btn btn-warning btn-sm">ویرایش</router-link>
                        <button v-if="adminHasPermission(PERM_ADMIN_DESTROY)" class="btn btn-danger btn-sm float-left" @click="deleteItem()">حذف</button>
                        <button v-if="adminHasPermission(PERM_ADMIN_UPDATE)" class="btn btn-info btn-sm float-left" @click="changePass()">ریست کردن رمز</button>
                    </div>
                </div>
            </card-component>
        </div>

        <div class="col-md-6 mx-auto" v-if="adminHasPermission(PERM_ADMIN_ROLE)">
            <card-component title="نقش ها" >
                <div class="col-sm-12" >
                    <div class="row mb-2" v-for="(role,index) in roles">


                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="checkbox" class="custom-control-input" :id="'role'+index" :checked="adminRoleIds.indexOf(role.id) != -1" @click="roleOperation(role)">
                            <label class="custom-control-label" :for="'role'+index">
                            </label>
                            <router-link :to="'/role/'+role.id"><span class="switch-label"  v-html="role.desc"></span></router-link>
                        </div>


                    </div>
                </div>
            </card-component>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            item: {},
            roles: {},
            adminRoleIds: [],

            newPassword: '',
            newPasswordRepeat: '',
            repeatErrorText: '',
            repeatClass: '',

            statuses: [],
        }
    },
    methods: {
        fetchData(){
            showLoading();
            axios.get('/admin/' + this.$route.params.id)
                .then(response => {
                    checkResponse(response.data, response => {
                        this.item = response.item

                        this.item.roles.forEach((role)=>{
                            this.adminRoleIds.push(role.id);
                        })
                    }, true)
                })
        },

        deleteItem() {
            confirm2('از حذف این آیتم اطمینان دارید؟', 'حذف', () => {
                showLoading();
                axios.delete('/admin/' + this.$route.params.id)
                    .then(response => {
                        checkResponse(response.data)
                    })
            })
        },

        getRoles() {
            showLoading();
            axios.get('/role')
                .then(response => {
                    checkResponse(response.data, () => {
                        this.roles = response.data.items;
                    }, true)

                });
        },

        roleOperation(role) {
            console.log('eeeee')
//                if(this.is_root!=1){
//                    alert2("برای تغییر نقش ها باید با دسترسی root وارد شوید", "خطا", 'error');
//                    return ;
//                }
            showLoading();
            axios.post('/admin/role', {
                admin_id: this.item.id,
                role_id: role.id,
                operation: this.adminRoleIds.indexOf(role.id) != -1 ? 'remove' : 'assign',
            })
                .then(response => {
                    checkResponse(response.data);
                });
        },
        changePass() {
            confirm2('آیا از تغییر رمز این مدیر اطمینان دارید؟', 'تغییر رمز', () => {
                showLoading();
                axios.post('/admin/newPassword', {
                    id: this.$route.params.id,
                    password: this.newPassword,
                })
                    .then(response => {
                        checkResponse(response.data, () => {
                            this.newPassword = '';
                            this.newPasswordRepeat = '';
                        })
                    })
            })
        },

        checkRepeat() {
            if (this.newPassword != this.newPasswordRepeat) {
                this.repeatClass = 'text-danger';
                this.repeatErrorText = 'رمز عبور های جدید همخوانی ندارند!';
            } else {
                this.repeatClass = '';
                this.repeatErrorText = '';
            }
        },
    },
    watch: {
        newPassword() {
            this.checkRepeat();
        },
        newPasswordRepeat() {
            this.checkRepeat();
        }
    },
    mounted() {
        this.fetchData()
        this.getRoles()

        this.statuses = Tools.utils("adminStatusText");
    },
}
</script>
