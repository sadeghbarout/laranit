<script setup>
import Multiselect from 'vue-multiselect'
import {onMounted, ref} from "vue";

const props = defineProps({
    title: {
        type: String,
        default: null
    },
    options: Array,
    placeholder: {
        type: String,
        default: 'یک یا چند مورد را انتخاب کنید',
    },
    label: {
        type: String,
        default: 'label',
    },
    trackBy: {
        type: String,
        default: 'value',
    },
    colum:{
        type:Boolean,
        default:true,
    },
})

const model = defineModel();
const optionsList = ref([]);

function addTag(newTag) {
    const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
    }
    optionsList.value.push(tag)
    model.value.push(tag)
}

onMounted(() => {
    optionsList.value = props.options
})
</script>

<template>
    <div class="row">
        <div v-if="title" class="" :class="{'col-4 d-flex align-items-center': !colum, 'px-1': colum}">
            <label  class="text-gray-dark fw-700" style="padding-bottom: 4px;">{{title}}</label>
        </div>
        <div class="mx-0 px-0" :class="{'col-12': colum || !title, 'col-8': !colum}"   style="padding: 0 14px !important;">
            <multiselect :placeholder="placeholder" v-model="model" :label="label" :track-by="trackBy" :options="optionsList" :multiple="true" :taggable="true" @tag="addTag" dir="rtl"></multiselect>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.multiselect {
    direction: rtl;
    text-align: right;
}
</style>

