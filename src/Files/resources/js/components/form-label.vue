/<template>
    <div>
        <div class="row border-bottom pt-1">
            <div class="col-6 col-lg-4 flex-center-right">
                <p v-html="title" class="font-weight-bold"></p>
            </div>
            <div class="col-6 col-lg-8 flex-center-right" >
                <label :class="classes" v-if="to!=undefined" ><router-link v-html="val" :to="to"></router-link></label>
                <label :class="classes" v-else-if="href!=undefined" ><a :href="href" v-html="val" :target="target" ref="aLink" @click="openModalIfImage($event)"></a></label>
                <div v-else-if="progressBar!=undefined" class="progress progress-bar-success mt-1 mb-0" dir="ltr" style="height:15px">
                    <div class="progress-bar" role="progressbar" :style="'width:'+val+'%'" :aria-valuenow="val" aria-valuemin="0" aria-valuemax="100" style="height:15px">{{val+'%'}}</div>
                </div>
                <label :class="classes" v-else="" >
                    <span v-html="val" :id="id"></span>
                    <slot></slot>
                </label>
                <i v-if="itemId!=undefined && id!=undefined" class="fa fa-pencil text-warning cursor-pointer" @click="edit()"></i>
                <div v-if="copy!=undefined && id!=undefined" class="d-inline">
                    <i  class="fa fa-clone text-warning cursor-pointer" @click="copyField()"></i>
                    <input :id="'copyfield'+id" class="d-none">
                </div>
            </div>
        </div>

        <modal-component modalId="expandImageModal" modalSize="modal-lg">
            <div class="col-100 text-center">
                <img class="img-fluid" id="expandImage">
            </div>
        </modal-component>

    </div>
</template>

<script>
    export default {
        props:{
            title:[String, Number],
            val:[String, Number],
            to:[String, Number],
            href:[String, Number],
            classes:[String, Number],
            id:[String, Number],
            itemId:[String, Number],
            options:[String, Number],
            optionsVal:[String, Number],
            secondItemId:[String, Number],
            dateInput:[String, Number],
            progressBar:[String, Number],
            target:[String, Number],
            copy:[String, Number],
            editUrl:[String, Number],
            reloadAfterEdit:[String, Number]
        },
        methods:{
            edit(){
                if(this.options){ // edit with drop down

                    let items=[];
                    this.options.forEach((item)=>{
                        items[item[1]]=item[0];
                    });
                    swal({
                        title: 'انتخاب '+ this.title,
                        input: 'select',
                        inputValue: this.optionsVal,
                        inputOptions:items,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value == '') {
                                    resolve('You need to select one :)')
                                } else {
                                    resolve()
                                }
                            })
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#7367F0',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ثبت',
                        cancelButtonText: 'لفو',
                        showLoaderOnConfirm: true,
                    }).then((result) => {
                        if (result.value) {

                            let newValue=result.value;

                            // showLoading();
                            let path=this.$route.path.split('/');
                            var editPath = this.editUrl === undefined ? path[1] : this.editUrl;
                            axios.patch('/'+editPath+'/editing/'+this.itemId,
                                {
                                    param:this.id,
                                    value:newValue,
                                })
                                .then((response) => {
                                    checkResponse(response.data,()=>{
                                        this.val=items[newValue];
                                        this.optionsVal=newValue;

                                        if(this.reloadAfterEdit)
                                            window.location.reload();
                                    });
                                })
                        }
                    })
                }else{ // edit with input box
                    prompt2(this.title+" :",this.val,(value)=>{
                        showLoading();
                        let path=this.$route.path.split('/');
                        var editPath = this.editUrl === undefined ? path[1] : this.editUrl;
                        axios.patch('/'+editPath+'/editing/'+this.itemId,
                            {
                                param:this.id,
                                value:value,
                                secondItemId : this.secondItemId,
                            })
                            .then((response) => {
                                checkResponse(response.data,()=>{
                                    this.val=value;
                                    if(this.reloadAfterEdit)
                                        window.location.reload();
                                });
                            })
                    });
                }

            },



            copyField(){
                var text = document.getElementById(this.id).innerHTML;
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                alert2("فیلد مورد نظر در کلیپ بورد کپی شد",'کپی شد','success');

            },



            openModalIfImage(e){
                var link = this.$refs.aLink.href;
                var parts = link.split('.').reverse();
                var extension = parts[0];
                if(extension == "jpg" || extension == "png" || extension == "jpeg"){
                    $('#expandImageModal').modal('show');
                    $('#expandImage').attr('src',link);
                    e.preventDefault();
                }
            },


        },

        mounted(){
        }
    }
</script>
