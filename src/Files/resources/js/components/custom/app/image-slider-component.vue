<template>
    <div class="mt-1">

        <i class="btn btn-outline-primary fas fa-plus cursor-pointer mb-2"  @click="uploadImage()"></i>
        <i class="btn btn-outline-info fas fa-th-large cursor-pointer mb-2" v-if="displayType == 'slider' " @click="changeDisplayType('blocks')"></i>
        <i class="btn btn-outline-info fas fa-square cursor-pointer mb-2" v-if="displayType == 'blocks' " @click="changeDisplayType('slider')"></i>

        <div v-if="images.length > 0">
            <div id="demo" class="carousel slide w-100" data-ride="carousel" v-if="displayType == 'slider' ">
                <ul class="carousel-indicators">
                    <li v-for="(image,index) in images" data-target="#demo" :data-slide-to="index" :class="index==0?'active':''"></li>
                </ul>

                <div class="carousel-inner w-100">
                    <div :class="['carousel-item  w-100', index == 0 ? 'active':'']"  v-for="(image,index) in images">
                        <i class="fas fa-trash text-danger cursor-pointer fs-20" @click="deleteImage(image,index)"></i>
                        <img :src="image.image" class="w-100 rounded cursor-pointer" data-toggle="modal" data-target="#expandModal" @click="expandImage(image)">
                    </div>
                </div>

                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>

            <div class="row" v-if="displayType == 'blocks' ">
                <div class="col-sm-6 p-1" v-for="(image,index) in images">
                    <i class="fa fa-trash text-danger cursor-pointer float-right fs-20" @click="deleteImage(image,index)"></i>
                    <img :src="image.image" class="w-100 rounded cursor-pointer" data-toggle="modal" data-target="#expandModal" @click="expandImage(image)">
                </div>
            </div>
        </div>
        <p v-else class="alert alert-primary text-center m-1">
            لطفا عکس اضافه کنید
        </p>

        <!-- The Expand Modal -->
        <div class="modal fade" id="expandModal">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <img class="img-fluid" id="expandedImage">
                </div>

            </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props:{
            images : Array,
            targetId : [String,Number],
            targetType : String,
            url : String,
        },
        data(){
            return{
                displayType : 'slider',
            }
        },

        methods:{
            uploadImage(){
                var urlToUpload = this.url == undefined ? ('/image/'+this.targetType+'/'+this.targetId) : this.url
                uploadFileDialog("عکس خود را انتخاب کنید",urlToUpload,(response)=>{
                    checkResponse(response.data, () => {
                        this.images.push(response.data.image);
                        // window.location.reload();
                    });
                });
            },


            deleteImage(image,index){
                confirm2('از حذف عکس اطمینان دارید؟','حذف',()=>{
                    axios.delete('/image/'+image.id)
                    .then(response=>{
                        checkResponse(response.data,()=>{
                            this.images.splice(index,1);
                            // window.location.reload();
                        });
                    })
                })
            },



            expandImage(image){
                $('#expandedImage').attr('src',image.image)
            },


            changeDisplayType(type){
                this.displayType = type;
            },
        },

        mounted(){
        }
    }
</script>
