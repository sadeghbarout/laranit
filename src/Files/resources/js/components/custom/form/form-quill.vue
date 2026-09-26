<script setup>
import {QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import {ref, watch} from "vue";

const props= defineProps({
    title: {
        type: String,
        default: null
    },
})

const modal = defineModel();
const myEditor = ref();
const isSetValue = ref(false);
const isEditorInit = ref(false);

watch(modal, () => {
    editorInit();
});

function textChange() {
    if (isEditorInit.value) {
        modal.value = myEditor.value.getHTML()
    }
}

function editorInit() {
    if (modal.value && !isSetValue.value) {
        myEditor.value.setHTML(modal.value);
        isSetValue.value = true;
    }
    isEditorInit.value = true;
}

</script>

<template>
    <div class="input-group d-flex flex-column">
        <label v-if="title" for="inputName">{{ title }}</label>
        <div>
            <QuillEditor ref="myEditor" theme="snow" @ready="editorInit" @textChange="textChange" class="quill-editor"/>
        </div>
    </div>
</template>

<style>
.quill-editor {
    height: 200px; /* تنظیم ارتفاع ویرایشگر */
    direction: rtl;
    text-align: right;
}

.quill-editor .ql-editor {
    direction: rtl;
    text-align: right;
}
</style>
