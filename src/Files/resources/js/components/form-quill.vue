<script setup>
import {QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import {ref, watch} from "vue";

const props= defineProps({
    title: {
        type: String,
        default: null
    },
    modelValue:{type:String}
})

const emit = defineEmits(['update:modelValue'])

// const modal = defineModel();
const myEditor = ref();
const isEditorInit = ref(false);

watch(()=>props.modelValue, () => {
    editorInit();
});

function textChange() {
    if (isEditorInit.value) {
        emit('update:modelValue', myEditor.value.getHTML())
    }
}

function editorInit() {
    if(props.modelValue){
        myEditor.value.setHTML(props.modelValue);
    }
    isEditorInit.value = true;
}

</script>

<template>
    <div class="input-group d-flex flex-column">
        <label v-if="title" for="inputName">{{ title }}</label>
        <div>
            <QuillEditor ref="myEditor" theme="snow" @ready="editorInit" @textChange="textChange" />
        </div>
    </div>
</template>
