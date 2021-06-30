<template>
    <th @click="sortList()" class="cursor-pointer" :style="[styles]">
            <i class="fa fa-angle-down sorting-down"></i>
            <i class="fa fa-angle-up sorting-up"></i>
        <span v-html="text"></span>
    </th>
</template>

<script>
    export default {
        props: ['name','text','styles'],
        model: {
            prop: 'val',
            event: 'input'
        },

        data(){
            return{
            }
        },
        methods:{
            sortList(){
                if(this.$parent.sortType == 'asc'){
                    this.$parent.sortType = 'desc';
                }
                else{
                    this.$parent.sortType = 'asc';
                }
                this.$parent.sort = this.name;
                this.$parent.fetchData(1);
            }
        },

        mounted() {

        },
    }
</script>
