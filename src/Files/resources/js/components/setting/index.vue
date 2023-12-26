<template>
    <div class="mt-5 mx-auto max-content">
        <div class="w-100">


            <card-component title="تنظیمات">
                <div class="col-12">

                    <form-inputs title='حداقل ورژن برنامه' placeholder="0" v-model="settings.minimumAppVersionAndroid"></form-inputs>
                    <form-inputs title=' ورژن جدید برنامه' placeholder="0" v-model="settings.latestAppVersionAndroid"></form-inputs>

                    <button class="btn btn-warning mt-4 w-100" @click="update()">ذخیره</button>


                </div>
            </card-component>

        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            settings: {
                minimumAppVersionAndroid:'',
                latestAppVersionAndroid:'',
            },
            loaded:false,
        }
    },
    methods:{
        fetchData(){
            axios.get('/setting')
                .then(response => {
                    checkResponse(response.data, response => {
                        this.settings = response.settings
                        this.loaded = true
                    }, true)
                })
        },
        update(){

            var formData = new FormData();
            formData.append('minimumAppVersionAndroid', this.settings.minimumAppVersionAndroid)
            formData.append('latestAppVersionAndroid', this.settings.latestAppVersionAndroid)

            axios.post('/setting/update',formData)
                .then(response=>{
                    checkResponse(response.data)
                })
        },
    },
    mounted() {
        this.fetchData()
    }
}
</script>
