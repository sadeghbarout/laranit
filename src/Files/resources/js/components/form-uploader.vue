<template>
    <div >
        <div class="col-12">
            <div class="form-group row cursor-pointer" :class="{'row': !colum, 'd-flex flex-column': colum}">
                <div v-if="title" :class="{'col-4 align-items-center px-0': !colum}" class="d-flex align-items-center">
                    <span v-text="title" class="text-gray-dark fw-700 fs-12"></span>
                </div>
                <div :class="{'col-8 mx-0 px-0': !colum}" :style="!colum?`padding-left: 26px !important;`:``">
                    <input @change="onchange" class="fileSelector custom-file-input d-none" :id="id" ref="fileInput" :name="name" type="file" :required="required!=undefined" :accept="accept" :multiple="multiple">
                    <div @click="$refs.fileInput.click" :class="['border-primary rounded flex-center-center', fileSelected ? 'btn-selected-file' : '' ]" style="height:35px">
                        <div v-if="fileSelected" class="flex-center-center text-dark">{{file.name}}</div>
                        <div v-else>انتخاب فایل</div>
                    </div>
                    <p v-if="w && h" class="text-primary"><small class="text-muted">فایل باید در ابعاد {{w}} در {{h}} باشد</small></p>
                </div>
                <!--todo: <img class="col" style="max-height: 100px;max-width: 100px" :src="modelValue" />-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
//            modelValue: [String,File], //todo:
        title: String,
        val: [String, Number],
        id: String,
        name: String,
        required: String,
        multiple: Boolean,
        w: [String, Number],
        h: [String, Number],
        accept: String,
        colum: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            fileSelected: false,
            file: '',
        }
    },
    model: {
        prop: 'val',
        event: 'input',
    },
    methods: {
        onchange(event) {
            if (this.multiple) {
                this.file = event.target.files;
            } else {
                this.file = event.target.files[0];
            }
            if (this.file != "" && this.file != null && this.file != undefined)
                this.fileSelected = true;
            else
                this.fileSelected = false;

            console.log(this.file);
//                this.imageSrc = URL.createObjectURL(event.target.files[0]); // todo


            this.$emit('update:modelValue', this.file);
        }
    }
}
</script>

<style>
.btn-selected-file {
    background-color: #99d9b4 !important;
}
</style>
