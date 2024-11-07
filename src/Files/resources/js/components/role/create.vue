<template>
    <div>
        <div style="max-width: 600px;" class="mx-auto pt-4">
            <card-component :title=" !id ? '  ثبت رکورد جدید ' : 'ویرایش '  ">
                <div class="col-12">
                    <form @submit.prevent="submitForm()">
                        <form-inputs title="نام انگلیسی" v-model="role.name"></form-inputs>
                        <form-inputs title="نام فارسی" v-model="role.desc"></form-inputs>

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
