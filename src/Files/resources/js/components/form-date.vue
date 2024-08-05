<template>
    <div>
        <div class="row form-group mx-1">

            <div class="p-0 col-md-12" >
                <p class="m-0" v-text="title"></p>
                <date-picker  v-model="inputVal" :type="type" :format="format" :locale="locale" :placeholder="placeholder" :key="renderKey" :clearable="clearable" :disabled="disabled"></date-picker>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            val: [String, Number],
            modelValue: [String, Number],
            title: String,
            placeholder: String,
            id: String,
            classes: String,
            wrapperClasses: String,
            ref: String,
            locale: String,
            type: String,
            format: String,
            clearable: String,
            readOnly:{
                type:Boolean,
                default:false,
            },
            disabled:{
                type:Boolean,
                default:false,
            },
        },

        data(){
            return{
                renderKey : 1,
            }
        },

        mounted() {
            // this.inputVal=this.val==undefined?'':this.val;
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
        watch:{
            modelValue:function(val){
                this.renderKey = Math.random();
            }
        }

    }
</script>
