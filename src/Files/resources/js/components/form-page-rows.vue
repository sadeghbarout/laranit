<template>
    <div class="form-inputs p-0" style="min-width:150px">
        <div class="col-xl-12 p-0">
            <fieldset class="form-group">
                <!-- <select  class="form-control" id="rows" @input="$emit('input', $event.target.value)"> -->
                <select  class="form-control" id="rows" v-model="rowsCount" @change="fD()">
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
        props: ['val'],
        model: {
            prop: 'val',
            event: 'input'
        },
        data(){
            return{
                rowsCount : 10,
            }
        },
        methods: {
            fD(){
                this.$emit('input',this.rowsCount)
            },
        },
    }
</script>
