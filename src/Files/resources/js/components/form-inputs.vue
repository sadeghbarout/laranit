<template>
    <div class="form-group row">
        <div class="col-sm-4" v-if="title">
            <label v-html="title"></label>
        </div>
        <div :class="[title === undefined ? 'col-12' : 'col-sm-8']">
            <input dir="auto" :type="type == undefined? 'text' : type "  v-model="inputVal"  :id="id" :ref="ref" :placeholder="placeholder !== undefined ? placeholder : '' "  :class="['form-control',classes]" :autocomplete="autocomplete" :maxlength="maxLength" :readonly="readOnly == true" :min="min" :max="max" >
        </div>
    </div>
</template>
<script>

    export default {
        props: {
            ssssss: [String, Number],
            val: [String, Number],
            modelValue: [String, Number],
            title: String,
            type: String,
            placeholder: String,
            noBorder: String,
            id: String,
            classes: String,
            wrapperClasses: String,
            ref: String,
            autocomplete: String,
            maxLength:String,
            readOnly:{
                type:Boolean,
                default:false,
            },
            min:[Number,String],
            max:[Number,String],
        },
        data(){
            return {
            }
        },
        methods:{
        }
        ,
        mounted() {
            this.inputVal=this.val==undefined?'':this.val;

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