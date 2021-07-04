<template>
    <fieldset class="form-group row cursor-pointer">
        <div v-if="title != undefined" class="col-md-4" style="padding:7px">
            <span v-text="title"></span>
        </div>
        <div :class="['',title != undefined? 'col-md-8': 'col-md-12' ]" @click="$refs.input.click()" >
            <input @change="onchange" class="fileSelector custom-file-input d-none" ref="input" :id="id" :name="name" type="file" :required="required!=undefined" :accept="accept">
            <div class="text-center p-1 rounded border p-0 flex-center-center overflow-hidden" style="min-hight:35px">
                <span v-if="fileSelected">{{file.name}}</span>
                <span v-else>انتخاب فایل</span>
            </div>
            <p v-if="w!=undefined && h != undefined" ><small class="text-primary">فایل باید در ابعاد {{w}} در {{h}} باشد</small></p>
        </div>

    </fieldset>
</template>

<script>
    export default {
        props:{
            title : String,
            val : [String,Number],
            id : String,
            name : String,
            required : String,
            w : [String,Number],
            h : [String,Number],
            accept : String,
        },
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

                console.log(this.file);

                this.$emit('update:modelValue',this.file);
            }
        }
    }
</script>
