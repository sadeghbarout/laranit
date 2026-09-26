<script setup>
defineProps({
    title: {
        type: String,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div class="table-responsive position-relative overflow-auto px-1">
        <div v-if="disabled" class="position-absolute w-100 h-100" style="top: 0; right: 0;background-color: rgba(255,255,255,0.3)"></div>

        <div class="d-flex">
            <div class="card-title" v-if="title" v-html="title"></div>
            <slot name="header"/>
        </div>

        <table class="table table-bordered custom-table">
            <slot/>
        </table>
    </div>
</template>
<style>
.custom-table td {
    width: 25%;
    white-space: nowrap;
}
.custom-table tr, .custom-table td {
    border: 1px solid #CBD9EE !important;
    color: #4e4e4e !important;
}

.custom-table tr:nth-child(even) {
    background-color: #F1F4FB;
}

.custom-table tr:nth-child(odd) {
    background-color: #fff;
}

.custom-table tr {
    font-size: 13px;
    text-align: center;
}

.custom-table-bold-cell {
    font-size: 14px;
    font-weight: 700;
    text-align: start;
}
</style>
