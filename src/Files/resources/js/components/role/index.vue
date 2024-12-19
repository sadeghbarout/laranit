<template>
    <div id="data-list-view" class="data-list-view-header">

        <!-- list -->

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="content-header-title float-left mb-0">سطوح دسترسی</h2>
            </div>
            <div class="d-flex" style="gap:8px;">
                <router-link  v-if="adminHasPermission(PERM_ROLE_STORE)" to="/role/create" class="btn btn-primary">
                    <span>
                        <i class="fas fa-plus"></i> جدید
                    </span>
                </router-link>
                <form-page-rows/>
            </div>
        </div>


        <div class="table-responsive " v-if="items.length > 0">
            <table class="table  data-list-view dataTable px-0">
                <thead>
                <tr>
                    <th>
                        <check-td header="true"></check-td>
                    </th>
                    <th-sort text="شناسه" name="id"></th-sort>
                    <th-sort text="نام فارسی" name="desc"></th-sort>
                    <th-sort text="نام" name="name"></th-sort>
                </tr>
                </thead>
                <tbody >
                <tr v-for="(item, index) in items"  :id="'row'+item.id">
                    <td>
                        <check-td ></check-td>
                    </td>
                    <td >  <router-link :to="'/role/'+item.id">{{item.id}}</router-link> </td>
                    <td class="product-name"> <router-link :to="'/role/'+item.id">{{item.desc}}</router-link> </td>
                    <td class="product-name"> <router-link :to="'/role/'+item.id">{{item.name}}</router-link> </td>
                </tr>
                </tbody>
            </table>
        </div>
        <pagination :pages="pageCount" v-model="page" @pageChanged="fetchData()"></pagination>
        <div v-if="items.length == 0" class="alert alert-primary text-center w-100 mt-2">آیتمی یافت نشد</div>
        <!-- / -->



    </div>
</template>
<script>

    export default {
        mixins:[window.urlMixin],

        data(){
            return {
                pageRows: 10,
                items: {},
                page: 1,
                pageCount: 1,
                types: [],
                selectedIds : [],
                sort : '',
                sortType : 'desc',
            }
        },
        methods: {
            fetchData(){
                if (this.page == '...')
                    return

                axios.get('/role', {
                    params: {
                        'pageRows': this.pageRows,
                        'page': this.page,
                        'sort': this.sort,
                        'sort_type': this.sortType,
                    }
                })
                .then(response => {
                    checkResponse(response.data,()=>{
                        this.items = response.data.items;
                        this.pageCount = response.data.page_count;
                    },true);
                })
            },





        },
        mounted() {
            this.fetchData();
        }
    }
</script>
