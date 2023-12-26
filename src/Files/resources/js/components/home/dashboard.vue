<template>
    <div class="container-fluid">
        <div class="row" id="app" >


            <div class="col-lg-3">
                <div class="small-box text-white w-100 bg-primary" style="background: rgb(255, 102, 0);">
                    <div class="w-100 d-flex align-items-center justify-content-between p-2">
                        <div class="inner">
                            <h3  v-text="items.adminsCount"></h3>
                            <p>مدیران</p></div>
                        <i class="fas fa-users icon" style="font-size: 55px;"></i></div>
                    <a href="/user" class="small-box-footer"> اطلاعات بیشتر ... <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>


        </div>
    </div>
</template>

<script>


    export default {

        data(){
            return {
                items:{
                },
                input : '',
            }
        },
        methods: {
            fetchData(){
                axios.get('/dashboard/info')
                    .then(response=>{
                        checkResponse(response.data,()=>{
                            this.items = response.data.items
                        },true)
                    })
            },


        },
        mounted(){
            this.fetchData()
        },
        watch:{
           
        }
    }
</script>
