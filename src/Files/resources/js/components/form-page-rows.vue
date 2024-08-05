<template>
    <div class="form-inputs p-0" >
        <div class="col-xl-12 p-0">
            <fieldset class="form-group m-0">
                <select  class="form-control" id="rows" v-model="rowsCount" @change="fD()" >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </select>
            </fieldset>
        </div>
    </div>
</template>

<script>
    export default {

        props:{
            modelValue: [String, Number],
        },

        data(){
            return{
                rowsCount : 10,
            }
        },

        mounted() {
            this.inputVal=10;
        },

        methods: {
            fD(){
                this.$parent.pageRows = this.rowsCount;
                this.$parent.page = 1
                this.$parent.fetchData();
            },
        },

        // computed: {
        //     inputVal: {
        //         get() {
        //             return this.modelValue;
        //         },
        //         set(inputVal) {
        //             this.$emit('update:modelValue', inputVal);
        //         }
        //     }
        // },

    }
</script>
