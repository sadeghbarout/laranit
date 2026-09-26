<template>
    <div id="data-list-view" class="data-list-view-header">

        <!-- list -->

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="content-header-title float-left mb-0">سطوح دسترسی</h2>
                <button @click="fetchData()" class="btn btn-outline-primary">بروزرسانی</button>
            </div>
            <div class="d-flex" style="gap:6px;">
                <router-link  v-if="adminHasPermission(PERM_ROLE_STORE)" to="/role/create" class="btn btn-primary">
                    <span>
                        <i class="fas fa-plus"></i> جدید
                    </span>
                </router-link>
                <form-page-rows/>
            </div>
        </div>


        <div class="table-responsive table-list">
            <table class="table  data-list-view px-0">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نام فارسی</th>
                    <th>نام</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody >
                <tr v-for="(item, index) in items"  :id="'row'+item.id">
                    <td >  <router-link :to="'/role/'+item.id">{{item.id}}</router-link> </td>
                    <td class="product-name"> <router-link :to="'/role/'+item.id">{{item.desc}}</router-link> </td>
                    <td class="product-name"> <router-link :to="'/role/'+item.id">{{item.name}}</router-link> </td>
                    <td>
                        <div class="d-flex" style="gap: 6px;">
                            <router-link :to='"/role/"+item.id' class="btn btn-warning btn-sm">مشاهده</router-link>
                            <button @click="seeAdmins(item)" class="btn btn-success btn-sm">کارشناسان</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <pagination :pages="pageCount" v-model="page" @pageChanged="fetchData()"></pagination>
        <!-- / -->

        <modal ref="adminsModal" title="کارشناسان" max-width="800px">
            <div style="max-height: 70vh;overflow: auto;">
                <div class="table-responsive table-list">
                    <table class="table data-list-view px-0">
                        <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>نام</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody >
                        <tr v-for="(admin, index) in admins" >
                            <td >  <router-link :to="'/admin/'+admin.id">{{admin.id}}</router-link> </td>
                            <td class="product-name"> <router-link :to="'/admin/'+admin.id">{{admin.name}}</router-link> </td>
                            <td class="product-name"> <router-link :to="'/admin/'+admin.id">{{admin.username}}</router-link> </td>
                            <td>
                                <div class="d-flex" style="gap: 6px;">
                                    <router-link :to='"/admin/"+admin.id' class="btn btn-warning btn-sm">مشاهده</router-link>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </modal>
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
            sort: {},
            filtersItems: [],

            admins: [],
        }
    },
    methods: {
        fetchData() {
            if (this.page == '...')
                return

            showLoading();
            axios.get('/role', {
                params: {
                    'pageRows': this.pageRows,
                    'page': this.page,
                    sort: this.sort,
                    filters: this.filtersItems,
                }
            })
                .then(response => {
                    checkResponse(response.data, () => {
                        this.items = response.data.items;
                        this.pageCount = response.data.page_count;
                    }, true);
                })
        },
        seeAdmins(item) {
            this.admins = [];
            showLoading();
            axios.get(`/role/findAdminByRole/${item.id}`)
                .then(response => {
                    checkResponse(response.data, (res) => {
                        this.$refs.adminsModal?.open();
                        this.admins = res.admins;
                    }, true);
                })
        }
    },
    activated() {
        this.fetchData();
    }
}
</script>
