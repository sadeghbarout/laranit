<template>
    <div>

        <!-- title and action -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="content-header-title float-left mb-0">لاگ ها</h2>
                <button @click="fetchData" class="btn btn-outline-primary">بروزرسانی</button>
            </div>
            <div class="d-flex" style="gap:6px;">
<!--                <excel-export-button v-if="adminHasPermission(PERM_LOGS_EXCEL)" @on-export="fetchData" :loading="isFetchingData"/>-->
                <custom-column-dialog type="logs" v-model="selectedColumnsData" :selectedColumnIds="selectedColumnIds"/>
                <form-page-rows/>
            </div>
        </div>
        <!-- / -->

        <!-- table -->
        <div class="table-responsive table-list">
            <table class="table data-list-view px-0">
                <thead>
                <tr>
                    <custom-column-th :selectedColumnsData="selectedColumnsData"  :sortOptions="sort" :filters="filtersItems"/>

                    <custom-th label="متن"  filter-type="text" :filters="filtersItems" :sortOptions="sort" name="text"/>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in items" :key="item.id" :id="'row'+item.id">
                    <custom-column-td :selectedColumnsData="selectedColumnsData" :to="`/log/${item.id}`" :item="item" ></custom-column-td>
                    <td>
                        <router-link :to='"/log/"+item.id'>{{ item.text?.length > 50? item.text.slice(0, 50) + '...' : item.text }}</router-link>
                    </td>
                    <td>
                        <router-link :to='"/log/"+item.id' class="btn btn-warning btn-sm">مشاهده</router-link>
                    </td>
                </tr>
                <tr>
                    <td>جمع کل</td>
                    <td :colspan="40">{{count}}</td>
                </tr>
                </tbody>
            </table>
            <pagination :pages="pageCount" v-model="page" @pageChanged="fetchData()"/>
        </div>
        <!-- / -->
    </div>
</template>

<script>
export default {
    props: {
        id: {default: ''},
        text: {default: ''},
        targetUserId: {default: ''},
        targetUserType: {default: ''},
        targetId: {default: ''},
        targetType: {default: ''},
        betweenDate: {default: []},
        onlyUpdateLogs: {default: false},
    },
    data() {
        return {
            selectedColumnIds: [],
            selectedColumnsData: [],

            items: [],
            pageCount: 1,
            page: 1,
            pageRows: 10,
            count: 0,


            sort: {},
            filtersItems: [],

            isFetchingData: false,
        }
    },
    methods: {
        fetchData(excelExport = 0) {
            if(this.isFetchingData){
                return;
            }

            this.isFetchingData = true

            axios.get('/log', {
                params: {
                    id: this.id,
                    text: this.onlyUpdateLogs? 'ویرایش' :this.text,
                    target_user_id: this.targetUserId,
                    target_user_type: this.targetUserType,
                    target_id: this.targetId,
                    target_type: this.targetType,
                    between_date: this.betweenDate,
                    export: excelExport,

                    page: this.page,
                    rows_count: this.pageRows,
                    sort: this.sort,
                    filters: this.filtersItems,
                },
            })
                .then(response => {
                    checkResponse(response.data, response => {
                        this.isFetchingData = false

                        if(excelExport===1){
                            window.location.replace(response.link)
                            return;
                        }

                        this.items = response.items
                        this.count = response.count;

                        this.pageCount = response.page_count;

                        this.selectedColumnsData=Tools.getSelectedColumnsData('logs',this.selectedColumnIds)
                    }, true)
                })
                .catch(() => {
                    this.isFetchingData = false
                })
        },
    },
    mounted() {
        this.fetchData()
    },
    activated() {
        this.fetchData()
    },
}
</script>
