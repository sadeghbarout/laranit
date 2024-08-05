<script setup>
const model = defineModel();

defineProps({
    title: {
        type: String,
        default: null
    },
    options: Array
});
</script>

<template>
    <div class="row" style="height: 51.39px;">
        <div class="col-4 text-gray-dark fw-700 d-flex align-items-center">{{title}}</div>
        <div class="row col-8 d-flex align-items-center" style="padding-left: 30px">
            <div v-for="option in options" class="flex items-center col-6 px-0" style="padding-right: 8px !important;">
                <button
                    type="button"
                    class="btn btn-sm w-100"
                    :class="[model == option.value? 'btn-primary': 'btn-outline-primary']"
                    @click="model=option.value"
                >{{ option.label }}
                </button>
            </div>
        </div>
    </div>
</template>
