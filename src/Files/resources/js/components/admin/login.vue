<template>
    <div class="row">
        <div class="w-100 mx-4">
            <div class="mx-auto" style="max-width: 600px;">
                <br>
                <br>
                <br>

                <div class="card">
                    <div class="card-header bg-dark d-flex align-items-center" style="height: 50px;">
                        <div class="card-title text-warning w-100">Login</div>
                        <slot name="header"></slot>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 text-center " id="loginApp">
                                <form class="form-horizontal" @submit.prevent="login()">
                                    <div class="box-body">

                                        <div class="form-group row m-1">
                                            <div class="p-0 col-md-12" >
                                                <p class="m-0">Username</p>
                                                <input type="text" placeholder="Username" class="form-control" v-model="username">
                                            </div>
                                        </div>

                                        <div class="form-group row m-1">
                                            <div class="p-0 col-md-12" >
                                                <p class="m-0">Password</p>
                                                <input type="password" placeholder="Password" class="form-control" v-model="password">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="d-flex align-items-center justify-content-right mt-3 ">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="checkbox" v-model="remember">
                                                            <span class="ml-4">remember me</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <button type="submit" class="btn btn-primary mx-auto w-100">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
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
                }).catch(error => {})
        }
    },
    mounted() {
        if(this.$user.isAuth){
            this.$router.push('/dashboard');
        }
    }
}
</script>
