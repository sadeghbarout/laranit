<template>
    <div id="data-list-view" class="data-list-view-header">

        <!-- filters -->
        <form @submit.prevent="page=1;fetchData()">
            <card-component>
                <div class='col-lg-3 col-md-6'>
                    <form-inputs title='شناسه' placeholder='شناسه' v-model='id'></form-inputs>
                </div>

                <div class='col-lg-3 col-md-6'>
                    <form-inputs title='نام' placeholder='نام' v-model='name'></form-inputs>
                </div>

                <div class='col-lg-3 col-md-6'>
                    <form-inputs title='نام کاربری' placeholder='نام کاربری' v-model='username'></form-inputs>
                </div>

                <div class='col-lg-3 col-md-6'>
                    <form-date title='از تاریخ ' placeholder='از تاریخ ' v-model='fromDate'></form-date>
                </div>

                <div class='col-lg-3 col-md-6'>
                    <form-date title='تا تاریخ ' placeholder='تا تاریخ ' v-model='toDate'></form-date>
                </div>

                <div class='col-lg-3 col-md-6 mt-2'>
                    <button type="submit" class="btn btn-outline-primary w-100">جستجو</button>
                </div>
            </card-component>
        </form>
        <!-- / -->

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="content-header-title float-left mb-0"> مدیران  </h2>
            </div>
            <div class="d-flex" style="gap:8px;">
                <router-link :to="'/admin/create'" class="btn btn-primary"  v-if="adminHasPermission(PERM_ADMIN_STORE)">
                    <span>
                        <i class="fas fa-plus"></i> جدید
                    </span>
                </router-link>
                <form-page-rows v-model="pageRows" @input="fetchData(1)"></form-page-rows>
            </div>
        </div>

        <div class="table-responsive table-list">
            <table class="table data-list-view px-0">
                <thead>
                <tr>
                    <th-sort text='شناسه' name='id'></th-sort>
                    <th-sort text='نام' name='name'></th-sort>
                    <th-sort text='نام کاربری' name='username'></th-sort>
                    <th-sort text='تاریخ ثبت' name='created_at'></th-sort>

                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in items" :key="item.id" :id="'row'+item.id">
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'>{{item.id}}</router-link>
                    </td>
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'>{{item.name}}</router-link>
                    </td>
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'>{{item.username}}</router-link>
                    </td>
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'>{{item.created_at_fa}}</router-link>
                    </td>
                </tr>
                </tbody>
            </table>
            <div v-if="items.length == 0" class="alert alert-warning text-center w-100 my-2">آیتمی یافت نشد</div>
            <pagination :pages="pageCount" v-model="page" @pageChanged="fetchData()"></pagination>
        </div>
        <!-- / -->

    </div>
</template>

<script>

export default {
    mixins:[window.urlMixin],
    data(){
        return {
            items: [],
            pageCount: 1,
            page: 1,
            pageRows: 10,
            selectedIds: [],
            sort: '',
            sortType: 'desc',

            id: '',
            name: '',
            username: '',
            fromDate: '',
            toDate: '',
        }
    },
    methods: {
        fetchData(){
            if (this.page == '...')
                return

            axios.get('/admin', {
                params: {
                    id: this.id,
                    name: this.name,
                    username: this.username,
                    from_date: this.fromDate,
                    to_date: this.toDate,

                    page: this.page,
                    rows_count: this.pageRows,
                    sort: this.sort,
                    sort_type: this.sortType,
                }
            })
                .then(response => {
                    checkResponse(response.data, response => {
                        this.items = response.items
                        this.pageCount = response.page_count;
                    }, true);
                })
        },

    },
    activated() {
        this.fetchData();
    }
}
</script>
