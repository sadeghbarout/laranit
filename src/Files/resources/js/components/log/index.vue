<template>
    <div id="data-list-view" class="data-list-view-header">

       <log-list-fragment
            ref="logList"
            :id="id"
            :text="text"
            :targetUserId="targetUserId"
            :targetUserType="targetUserType"
            :targetId="targetId"
            :targetType="targetType"
            :betweenDate="betweenDate"
            :onlyUpdateLogs="this.$route.path.includes('update')"
       />
    </div>
</template>
<script>
import LogListFragment from "@/components/log/fragments/log-list-fragment.vue";

export default {
    components: {LogListFragment},
    mixins: [window.urlMixin],
    data() {
        return {
            id: '',
            text: '',
            targetUserId: '',
            targetUserType: '',
            targetId: '',
            targetType: '',
            betweenDate: [],

            targetUserTypes: [],
            targetTypes: [],
        }
    },
    mounted() {
        this.userId = this.$route.query.user_id || '';

        this.targetUserTypes = Tools.utils('logTarget_user_types', true)
        this.targetTypes = Tools.utils('logTarget_types', true)
    },
}
</script>
