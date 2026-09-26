<template>
    <div class="row">
        <div class="col-12 mx-auto">

            <card-component title="مشخصات لاگ">
                <custom-table>
                    <tbody>
                    <tr>
                        <td-label title="شناسه">{{ item.id }}</td-label>
                        <td-label title="کاربر">
                            <router-link :to="`/${item.target_user_type}/${item.target_user_id}`">{{ item.target_user_type_text }} - {{ item.target_user?.name }}</router-link>
                        </td-label>
                    </tr>
                    <tr>
                        <td-label title="شناسه فرایند">{{ item.target_id }}</td-label>
                        <td-label title="فرایند">{{ item.target_type_text }}</td-label>
                    </tr>
                    <tr>
                        <td-label title="تاریخ ثبت">{{ item.date_fa }}</td-label>
                        <td-label title="متن">{{ item.text }}</td-label>
                    </tr>
                    </tbody>
                </custom-table>
            </card-component>

        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            item: {},
            targetUserType: '',
            targetUserTypes: [],
            targetType: '',
            targetTypes: [],
        }
    },
    methods: {
        fetchData() {
            showLoading();
            axios.get('/log/' + this.$route.params.id)
                .then(response => {
                    checkResponse(response.data, response => {
                        this.item = response.item
                    }, true)
                })
        },
    },
    mounted() {
        this.fetchData()

        this.targetUserTypes = Tools.utils('logTarget_user_types', false)
        this.targetTypes = Tools.utils('logTarget_types', false)
    },
}
</script>
