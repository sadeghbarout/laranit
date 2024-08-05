<template>
    <div>
        <section class="page-users-view">
            <form @submit.prevent="submitForm()">

            <div class="data-items pb-3">
                <div class="data-fields px-2 mt-3">
                    <form-inputs title="نام انگلیسی" v-model="role.name" icon="fa fa-tag" required="true"></form-inputs>
                    <form-inputs title="نام فارسی" v-model="role.desc" icon="fa fa-tag" required="true"></form-inputs>
                </div>
            </div>


            <div class="add-data-footer d-flex justify-content-around px-3 mt-2 mb-1">
                <div class="add-data-btn">
                <button class="btn btn-primary">ثبت</button>
                </div>
                <div class="cancel-data-btn">
                    <span class="btn btn-outline-danger">لغو</span>
                </div>
            </div>


            </form>
        </section>
    </div>
</template>

<script>

    export default {
        data(){
            return {
                id:'',
                role: {
                    name:'',
                    desc:'',
                },
                editMode : false,
            }
        },

        methods:{

            fetchItem(){
                axios.get('/role/'+this.id)
                .then(response=>{
                    this.role = response.data.item;
                })
            },

            submitForm(){
                var formData = new FormData();
                formData.append('name', this.role.name);
                formData.append('desc', this.role.desc);

                if(this.editMode){
                    formData.append('_method', "PATCH");
                    axios.post('/role/'+this.role.id,formData)
                    .then(response=>{
                        checkResponse(response.data);
                    })
                }
                else{
                    axios.post('/role',formData)
                    .then(response=>{
                        checkResponse(response.data);
                    })
                }
            },




        },

        mounted() {
            this.id = this.$route.params.id

            if (this.id !== '')
               this.fetchItem();

        },



         watch: {
            id: {
                handler (val, oldVal) {
                    this.id === undefined ? this.editMode = false : this.editMode = true;

                    if(this.editMode){
                        this.fetchItem();
                    }
                    else{
                        this.role = {};
                    }

                }
            }
        },


    }
</script>
