<template>
    <template v-for="column in selectedColumnsData">
        <custom-th
            :label="column.label"
            :filterType="column.filterType"
            :val="column.val"
            :name="column.id"
            :filters="filters"
            :sortOptions="sortOptions"
            :sortable="column.sortable!==0"/>
    </template>

</template>

<script>
export default {
    props: {
        selectedColumnsData: Array,
        filters: {
            default: [],
        },
        sortOptions: {
            default: null,
            type: Object
        },
    },
    data() {
        return {
            sortType:'',
            sort:''
        }
    },
    methods: {
        fetchData(){ // for th-sort internal functions
            this.$parent.sortType=this.sortType
            this.$parent.sort = this.sort;

            this.$parent.fetchData();
        }
    }
}
</script>
