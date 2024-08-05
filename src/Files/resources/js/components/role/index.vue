<template>
    <div id="data-list-view" class="data-list-view-header">

        <!-- list -->
        <div class="row">
            <div class="col-sm-12">
                <h2 class="content-header-title float-left mb-0">سطوح دسترسی</h2>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-sm-4">
                <div class="btn-group dropdown actions-dropodown d-inline p-1 pl-0 mx-auto">
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0; left: 0; transform: translate3d(0px, 45px, 0px);">
                        <a class="dropdown-item" href="#"><i class="feather icon-trash"></i>حذف</a>
                        <a class="dropdown-item" href="#"><i class="feather icon-archive"></i>آرشیو</a>
                        <a class="dropdown-item" href="#"><i class="feather icon-file"></i>چاپ</a>
                    </div>
                </div>
                <div>
                    <router-link v-if="adminHasPermission(PERM_ROLE_STORE)" to="/role/create" class="btn btn-outline-primary" >
                        <span>
                            <i class="fas fa-plus"></i> جدید
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="col-sm-6"></div>
            <div class="col-sm-2">
                <form-page-rows ></form-page-rows>
            </div>
        </div>


        <div class="table-responsive " v-if="items.length > 0">
            <table class="table  data-list-view dataTable">
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
