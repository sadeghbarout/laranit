<script setup>
import {ref, watch} from "vue";

const model = defineModel()

const props = defineProps({
    val: [String, Number],
    modelValue: [String, Number],
    title: String,
    placeholder: {
        type: String,
        default: '',
    },
    classes: String,
    wrapperClasses: String,
    ref: String,
    locale: {
        type: String,
        default: 'fa'
    },
    type: String,
    format: {
        type: String,
        default: 'YYYY-MM-DD'
    },
    displayFormat: {
        type: String,
        default: 'jYYYY/jMM/jDD'
    },
    rules: String,
    readOnly:{
        type:Boolean,
        default:false,
    },
    disabled:{
        type:Boolean,
        default:false,
    },
    colum: {
        type: Boolean,
        default: true,
    },
    range: {
        type: Boolean,
        default: false,
    },
    autoSubmit: {
        type: Boolean,
        default: true,
    },
    clearable: {
        type: Boolean,
        default: false,
    },
    min: {
        type: String,
        default: '',
    },
    max: {
        type: String,
        default: '',
    },
    allowedDates: {
        type: Array,
        default: null,
    },
})

const hiddenInp = ref(null)
watch(model, () => {
    if (model.value && model.value !== '') {
        let previousElement = hiddenInp.value.nextElementSibling;
        if (previousElement) {
            previousElement.remove();
        }
    }
})

function checkDate(formatted, dateMoment, checkingFor) {
    return !props.allowedDates.includes(formatted)
}
</script>

<template>
    <div>
        <div class="row form-group mx-0 px-0" :class="title && !colum? 'col-md-8': 'col-md-12' ">

            <div v-if="title && !colum" :class="{'col-md-4': title}" class="d-flex align-items-center w-100 px-0 mx-0">
                <p class="m-0" v-text="title"></p>
            </div>
            <div :class="title && !colum? 'col-md-8': 'col-md-12' " class=" w-100 mx-0 px-0">
                <div>
                    <div v-if="title && colum" style="padding-bottom: 2px">{{ title }}</div>
                    <div>
                        <!--                    <date-picker format="jYYYY-jMM-jDD"  :display-format="displayFormat" v-model="inputVal" :type="type" :locale="locale" :placeholder="placeholder" :key="renderKey" :clearable="clearable" :disabled="disabled"></date-picker>-->
                        <date-picker
                            :type="type"
                            :placeholder="placeholder"
                            v-model="model"
                            :format="format"
                            :display-format="displayFormat"
                            :range="range"
                            :clearable="clearable"
                            :auto-submit="autoSubmit"
                            :min="min"
                            :max="max"
                            :locale="locale"
                            :disabled="disabled"
                            :disable="checkDate"
                        />
                        <div>
                            <input type="hidden" ref="hiddenInp" :data-rules="rules" v-model="model">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
.vpd-icon-btn {
    background-color: #00ACC1 !important;
}
</style>
