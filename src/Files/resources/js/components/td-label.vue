<template>
    <td class="custom-table-bold-cell">{{ title }}</td>
    <td :colspan="colspan">
        <div class="d-flex align-items-center justify-content-center">
            <slot/>

            <div v-if="updatable" @click="$emit('onStartUpdate', title, updateType)" class="cursor-pointer text-warning"
                 style="padding: 4px">
                <i class="fas fa-pen"></i>
            </div>
        </div>
    </td>
</template>
<script>
export default {
    props: {
        title: String,
        colspan: {
            type: Number,
            default: 1
        },
        updatable: {
            type: Boolean,
            default: false,
        },
        updateType: {
            type: String,
            default: null,
        },
    }
}
</script>
