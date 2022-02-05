<template>
    <card-component title="ورود" >
        <div class="col-12 text-center " id="loginApp">
            <form class="form-horizontal" @submit.prevent="login()">
                <!-- {{csrf_field()}} -->
                <div class="box-body">
                    <form-inputs title="نام کاربری"  v-model="username"></form-inputs>
                    <form-inputs type="password" title="رمز عبور"  v-model="password"></form-inputs>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center justify-content-right mt-3 ">
                                <input type="checkbox" v-model="remember">
                                <span class="ml-4">مرا به خاطر بسپار</span>
                            </div>
                            <br>

                            <button type="submit" class="btn btn-primary mx-auto w-100">ورود</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </card-component>
</template>

<script>
export default {
    props:{

    },
    data(){
        return {
            username:'',
            password:'',
            remember:'',
        }
    },
    methods:{
        login(){
            axios.post('/admin/login', {
                    'username': this.username,
                    'password': this.password,
                    'remember': this.remember,
            })
            .then(response => {
                checkResponse(response.data,res=>{
                    preferences.set('admin_permissions',res.admin_permissions)
                    window.location.reload()
                });
            }).catch(error => {
            })
        }
    },

    mounted(){
    },
}
</script>

