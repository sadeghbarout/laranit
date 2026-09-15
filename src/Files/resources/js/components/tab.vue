<script setup>
defineProps({
    tabs: Array
})

const model = defineModel();
</script>
<template>
    <!--    <ul class="nav nav-tabs">-->
    <!--        <template v-for="tab in tabs">-->
    <!--            <li v-if="tab.hsePermission" class="nav-item fs-17 fw-700"  style="padding-right: 0 !important;"><a @click="model = tab.name " :class="['nav-link', model === tab.name? 'text-primary active' : 'text-gray-dark ']" data-toggle="tab" href="#">{{tab.title}}</a></li>-->
    <!--        </template>-->
    <!--    </ul>-->
    <div class="d-flex flex-wrap pb-2" style="gap: 6px">
        <template v-for="tab in tabs">
            <button
                v-if="tab.hsePermission"
                @click="model = tab.name "
                class="tab-main cursor-pointer"
                :class="[model === tab.name? 'tab-active text-white' : 'tab-inactive text-primary']"
            >{{ tab.title }}
            </button>
        </template>
    </div>
</template>

<style>
.tab-main {
    padding: 8px 16px;
    border-radius: 6px;
    border: 0;
}

.tab-active {
    background-color: rgb(118, 102, 237);
}

.tab-inactive {
    background-color: rgb(233, 231, 252);
}
</style>
