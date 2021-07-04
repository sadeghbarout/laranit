<template>
    <fieldset class="form-group row">
        <div v-if="title != undefined" :class="'col-md-'+labelColSize">
            <span v-text="title"></span>
        </div>
        <div :class="['p-0',title != undefined? 'col-md-'+textareaColSize: 'col-md-12' ]">
            <textarea class="form-control" rows="7" ref="textarea" cols="80" :id="id" :name="name" v-model="inputVal" v-html="val" :placeholder="placeholder !== undefined ? placeholder : ''"></textarea>
            <div v-if="copy!=undefined" class="d-inline" style="position: absolute;left: -15px;top: -4px;">
                <i  class="fas fa-clone text-warning cursor-pointer" @click="copyField()"></i>
            </div>
        </div>
    </fieldset>
</template>

<script>
    export default {
        props: {
            modelValue: String,
            title: String,
            val: [String, Number],
            id: [String, Number],
            name: String,
            labelSize: [String, Number],
            copy: String,
            placeholder: String,
        },
        data(){
            return{
                labelColSize : 4,
                textareaColSize : 8,
            }
        },
        methods: {
            clearInput(e){
                this.inputVal = '';
                $(e.target).next().val('');
                this.$emit('input', this.inputVal)
            },


            copyField(){
                var text = this.$refs.textarea.innerHTML;
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                alert2("فیلد مورد نظر در کلیپ بورد کپی شد",'کپی شد','success');

            }
        },
        mounted() {
            if(this.labelSize !== undefined){
                this.labelColSize = this.labelSize;
                this.textareaColSize = 12-this.labelSize;
            }
        },


        mounted() {
            this.inputVal=this.val; // todo : not works!

        },
        computed: {
            inputVal: {
                get() {
                    return this.modelValue;
                },
                set(inputVal) {
                    this.$emit('update:modelValue', inputVal);
                }
            }
        },

    }
</script>
