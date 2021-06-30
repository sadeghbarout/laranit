<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-2">
            <li :class="['page-item prev', page == 1 ? 'disabled': '' ]"><a class="page-link" @click="pageNumberOperation(-1)"></a></li>
            <li :class="['page-item', p==page?'active':'' ]" v-for="p in pages" @click="fD(p)" :key="p.id"><a class="page-link" >{{p}}</a></li>
            <li :class="['page-item next', pages.length == page ? 'disabled': '' ]"><a class="page-link" @click="pageNumberOperation(1)"></a></li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['val','pages','page'],
        model: {
            prop: 'val',
            event: 'input'
        },
        methods:{
            fD(p){
                this.$parent.fetchData(p);
            },

            pageNumberOperation(op){
                var newP = parseInt(this.page)+op;
                this.fD(newP);
            },
        },
    }
</script>
