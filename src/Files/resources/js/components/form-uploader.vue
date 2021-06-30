<template>
    <div>
        <div class="col-12 p-0">
            <fieldset class="form-group row cursor-pointer">
                <div v-if="title != undefined" class="col-md-4" style="padding:7px">
                    <span v-text="title"></span>
                </div>
                <div :class="['rounded border p-0 flex-center-center',title != undefined? 'col-md-8': 'col-md-12' ]" @click="$refs.input.click()" style="min-hight:35px">
                    <input @change="onchange" class="fileSelector custom-file-input d-none" ref="input" :id="id" :name="name" type="file" :required="required!=undefined" :accept="accept">
                    <div >
                        <span v-if="fileSelected">{{file.name}}</span>
                        <span v-else>انتخاب فایل</span>
                    </div>
                    <p v-if="w!=undefined && h != undefined" class="text-primary"><small class="text-muted">فایل باید در ابعاد {{w}} در {{h}} باشد</small></p>
                </div>

            </fieldset>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'val', 'id', 'name','required','w','h','accept'],
        data(){
            return{
                fileSelected: false,
                file: '',
            }
        },
        model: {
            prop: 'val',
            event: 'input',
        },
        methods:{
            onchange(event){
                this.file = event.target.files[0];
                if(this.file != "" && this.file != null && this.file != undefined)
                    this.fileSelected = true;
                else
                    this.fileSelected = false;

                this.$emit('input',this.file);
            }
        }
    }
</script>
