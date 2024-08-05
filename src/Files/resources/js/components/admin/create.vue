<template>
    <div>
        <div style="max-width: 600px;" class="mx-auto pt-4">
            <card-component :title=" !id ? '  ثبت رکورد جدید ' : 'ویرایش '  ">
                <div class="col-12">
                    <form @submit.prevent="formSubmission()">

                        <form-inputs title='نام نمایشی' v-model='item.name' type='text'></form-inputs>
                        <form-inputs title='نام کاربری' v-model='item.username' type='text'></form-inputs>
                        <form-inputs v-if="item.id==''" title='رمز عبور' v-model='item.password' type='password'></form-inputs>
                        <form-uploader title='عکس پروفایل' v-model='item.image'></form-uploader>

                        <br>
                        <button class="btn btn-warning w-100 mx-auto">ثبت</button>
                    </form>
                </div>
            </card-component>

        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                item: {
                    id: '',
                    name: '',
                    username: '',
                    password: '',
                    image: '',
                },
                id: '',
            }
        },
        methods: {

            formSubmission(){
                var formData = new FormData();
                formData.append('name', this.item.name);
                formData.append('username', this.item.username);
                formData.append('image', this.item.image);

                if (this.id) {
                    formData.append('_method', "patch");

                    axios.post('/admin/' + this.item.id, formData, {
                        headers: {'Content-Type': 'multipart/form-data'}
                    })
                        .then(response => {
                            checkResponse(response.data);
                        })
                }
                else {
                    formData.append('password', this.item.password);

                    axios.post('/admin', formData, {
                        headers: {'Content-Type': 'multipart/form-data'}
                    })
                        .then(response => {
                            checkResponse(response.data);
                        })
                }
            },


            fetchData(){
                axios.get('/admin/' + this.id)
                    .then(res => {
                        checkResponse(res.data, res => {
                            this.item = res.item
                        }, true)
                    })
            },
        },
        mounted(){
            this.id = this.$route.params.id

            if (this.id !== '')
                this.fetchData();
        },
    }
</script>
