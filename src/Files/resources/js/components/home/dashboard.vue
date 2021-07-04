<template>
    <div class="container-fluid">
        <div class="row" id="app" >

            <div class="col-lg-3 " id="xx">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 style="color: white" v-text="items.adminsCount"></h3>

                        <p>مدیران</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bars"></i>
                    </div>
                    <router-link to="/project" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i>اطلاعات بیشتر ...</router-link>
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
                            this.items = response.data
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
