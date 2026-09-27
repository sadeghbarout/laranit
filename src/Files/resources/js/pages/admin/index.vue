<template>
    <div id="data-list-view" class="data-list-view-header">

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="content-header-title float-left mb-0"> کارشناسان  </h2>
                <button @click="fetchData()" class="btn btn-outline-primary">بروزرسانی</button>
            </div>
            <div class="d-flex" style="gap:6px;">
                <form-page-rows/>
                <router-link :to="'/admin/create'" class="btn btn-primary"  v-if="adminHasPermission(PERM_ADMIN_STORE)">
                    <span>
                        <i class="fas fa-plus"></i> جدید
                    </span>
                </router-link>
            </div>
        </div>

        <div class="table-responsive table-list">
            <table class="table data-list-view px-0">
                <thead>
                <tr>
                    <custom-th label="شناسه"  filter-type="text" sortable :filters="filtersItems" :sortOptions="sort" name="id"/>
                    <custom-th label="نام"  filter-type="text" sortable :filters="filtersItems" :sortOptions="sort" name="name"/>
                    <custom-th label="نام کاربری"  filter-type="text" sortable :filters="filtersItems" :sortOptions="sort" name="username"/>
                    <custom-th label="وضعیت"  filter-type="adminStatusText" :filters="filtersItems" :sortOptions="sort" name="status"/>
                    <custom-th label="دسترسی ها"  />
                    <custom-th label="تاریخ ثبت"  filter-type="date" sortable :filters="filtersItems" :sortOptions="sort" name="created_at"/>
                    <th>عملیات</th>
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
                        <router-link :to='"/admin/"+item.id'>
                            <div :class="`badge badge-${item.status_color}`">{{item.status_text}}</div>
                        </router-link>
                    </td>
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'><p v-for="role in item.roles">{{role.desc}}</p></router-link>
                    </td>
                    <td class='product-name'>
                        <router-link :to='"/admin/"+item.id'>{{item.created_at_fa}}</router-link>
                    </td>
                    <td>
                        <router-link :to='"/admin/"+item.id' class="btn btn-warning btn-sm">مشاهده</router-link>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination :pages="pageCount" v-model="page" @pageChanged="fetchData()"></pagination>
        </div>
        <!-- / -->

        <list-refresh @refresh="fetchData"/>

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
            sort: {},
            filtersItems: [],
        }
    },
    methods: {
        fetchData(){
            if (this.page == '...')
                return

            axios.get('/admin', {
                params: {
                    page: this.page,
                    rows_count: this.pageRows,
                    sort: this.sort,
                    filters: this.filtersItems,
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
