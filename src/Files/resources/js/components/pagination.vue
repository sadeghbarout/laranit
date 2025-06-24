<template>
    <ul v-if="pages !== 1 && pages !== 0" class="pagination pagination-md d-flex align-items-center justify-content-center">
        <li :class="['page-item prev', localPage == 1 ? 'disabled': '' ]">
            <div class="btn-pagination" @click="pageNumberOperation(-1)">
                <i class="fas fa-angle-right"></i>
            </div>
        </li>
        <li :class="['page-item ', p==localPage?'active':'' ]" v-for="p in paginatedPages" @click="emitPageNumber(p)" :key="p.id"><a class="page-link btn" >{{p}}</a></li>
        <li class="btn-pagination " :class="['page-item next', pages <= localPage ? 'disabled': '' ]">
            <div class="btn-pagination" @click="pageNumberOperation(1)">
                <i class="fas fa-angle-right" style="transform: rotate(180deg);"></i>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
    props:{
        pages : [String,Number],
        modelValue : [String,Number],
    },
    data() {
        return {
            localPage: 1,
        }
    },
    methods: {
        emitPageNumber(p) {
            this.localPage = p;
            this.$emit('update:modelValue', p);
            this.$emit('pageChanged', p);
        },

        pageNumberOperation(op) {
            if (this.localPage === 1 && op === -1) {
                return;
            }

            if (this.localPage === this.pages && op === 1) {
                return;
            }

            this.localPage = parseInt(this.localPage) + op;
            this.emitPageNumber(this.localPage);
        },
    },
    mounted() {
        // this.emitPageNumber(this.localPage);
        this.$emit('update:modelValue', this.localPage)
    },
    computed: {
        paginatedPages() {
            return Tools.creatPaginatis(this.pages, this.localPage);
        }
    },

    watch: {
        modelValue() {
            this.localPage = this.modelValue;

        }
    }
}
</script>

<style>
.btn-pagination {
    width: 39px;
    height: 39px;
    background-color: #f0f0f0;
    color: #0009;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
