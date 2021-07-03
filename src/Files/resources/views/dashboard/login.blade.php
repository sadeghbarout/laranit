@extends('dashboard.layout-full')
@section('content')
    <div class="row">
        <div class="col-sm-6 mx-auto">

            <br>
            <br>
            <br>

            <card-component title="ورود" >
                <div class="col-12 text-center ">
                    <form class="form-horizontal" @submit.prevent="login()">
                        {{csrf_field()}}
                        <div class="box-body">
                            <form-inputs title="نام کاربری"  v-model="username"></form-inputs>
                            <form-inputs type="password" title="رمز عبور"  v-model="password"></form-inputs>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center justify-content-right">
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




        </div>
    </div>
@stop


@push('vue')
<script>

    vue={
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
                        checkResponse(response.data);
                    }).catch(error => {
                })
            }
        },
    }
</script>
@endpush

