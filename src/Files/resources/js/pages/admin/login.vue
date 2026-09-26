<template>
    <div>
        <div style="max-width: 800px" class="mx-auto">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0 p-0">

                    <div class="col-lg-6 d-lg-block d-none p-0">
                        <img :src="'/images/login.png'" alt="branding logo" style="width: 100%;height: 100%; object-fit: cover;">
                    </div>

                    <div class="col-lg-6 p-0">
                        <div class="card rounded-0 mb-0 px-2">
                            <div>
                                <div class="card-header pb-1">
                                    <div class="card-title">
                                        <h4 class="mb-0">ورود</h4>
                                    </div>
                                </div>
                                <p class="px-2">خوش آمدید ، لطفا به حساب کاربری خود وارد شوید</p>
                                <div class="card-content">
                                    <div class="card-body pt-1">
                                        <form @submit.prevent="login">
                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" id="user-name" v-model="username" placeholder="نام کاربری" >
                                                <div class="form-control-position">
                                                    <i class="fas fa-user" style="top:10px"></i>
                                                </div>
                                                <label for="user-name">نام کاربری</label>
                                            </fieldset>
                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                <input type="password" class="form-control" id="user-name" v-model="password" placeholder="رمز عبور" >
                                                <div class="form-control-position">
                                                    <i class="fas fa-unlock-alt" style="top:10px"></i>
                                                </div>
                                                <label for="user-name">رمز عبور</label>
                                            </fieldset>

                                            <div class="form-group d-flex justify-content-between align-items-center">
                                                <button type="submit" class="btn btn-primary float-right btn-inline w-100">ورود</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
        return {
            phone: '',
            username: '',
            password: '',
            fragment: 'phone',
        }
    },
    methods:{
        login(){
            showLoading();
            axios.post('/admin/login', {
                'username': this.username,
                'password': this.password,
            })
                .then(response => {
                    checkResponse(response.data,res=>{
                        window.location.replace('/')
                    });
                }).catch(error => {})
        }
    }
}
</script>
