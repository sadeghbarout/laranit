<template>
    <fieldset class="form-group row mb-2 flex-center-center">
        <div v-if="title != undefined" class="col-md-4">
            <span v-text="title"></span>
        </div>
        <div :class="['p-0',title != undefined? 'col-md-8': 'col-md-12' ]">
            <select  class="form-control" :id="id" :name="name" v-model="inputVal" :required="required!=undefined">
                <option v-if="emptyOption !== undefined" value="">انتخاب کنید</option>
                <option v-for="opt in options" v-text="opt[0]" :value="opt[1]" :selected="opt[1] == val" ></option>
            </select>
        </div>
    </fieldset>
</template>

<script>
    export default {
        props: {
            options: Array,
            modelValue: [String, Number],
            title: String,
            val: [String,Number],
            id: [String,Number],
            name: String,
            required: String,
            emptyOption: String,
        },
        watch:{
            options(val){
                if(this.inputVal=='' && this.selectedValue===undefined && val.length>0){
                    this.inputVal= val[0][1]
                }

            }
        },

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
