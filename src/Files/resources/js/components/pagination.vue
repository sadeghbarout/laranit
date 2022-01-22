<template>
    <ul class="pagination pagination-md d-flex align-items-center justify-content-center">
        <li :class="['page-item prev', localPage == 1 ? 'disabled': '' ]"><a class="page-link btn" @click="pageNumberOperation(-1)">Previous</a></li>
        <li :class="['page-item ', p==localPage?'active':'' ]" v-for="p in paginatedPages" @click="emitPageNumber(p)" :key="p.id"><a class="page-link btn" >{{p}}</a></li>
        <li :class="['page-item next', pages <= localPage ? 'disabled': '' ]"><a class="page-link btn" @click="pageNumberOperation(1)">Next</a></li>
    </ul>
</template>

<script>
    import Tools from '../tools'
    export default {
        props:{
            pages : [String,Number],
            modelValue : [String,Number],
        },
        data(){
            return{
                localPage : 1,
            }
        },
        methods:{
            emitPageNumber(p){
                this.localPage = p;
                this.$emit('update:modelValue',p);
                this.$emit('pageChanged',p);
            },

            pageNumberOperation(op){
                this.localPage = parseInt(this.localPage)+op;
                this.emitPageNumber(this.localPage);
            },
        },
        mounted(){
            // this.emitPageNumber(this.localPage);
            this.$emit('update:modelValue',this.localPage)
        },
        computed: {
            paginatedPages(){
                return Tools.creatPaginatis(this.pages, this.localPage);
            }
        },

        watch:{
            modelValue(){
                this.localPage=this.modelValue;

            }
        }
    }
</script>
