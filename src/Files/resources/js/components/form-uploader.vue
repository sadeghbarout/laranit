<template>
    <div>
        <div class="col-12">
            <div class="form-group row cursor-pointer" :class="{'row': !colum, 'd-flex flex-column': colum}">
                <div v-if="title" :class="{'col-6 align-items-center px-0': !colum}" class="d-flex align-items-center">
                    <span v-text="title" class="text-gray-dark fw-700 fs-12"></span>
                </div>
                <div :class="{'col-6 mx-0 px-0': !colum}" :style="!colum?`padding-left: 26px !important;`:``">
                    <input @change="onchange" class="fileSelector custom-file-input d-none" :id="id" ref="fileInput"
                           :name="name" type="file" :required="required!=undefined" :accept="accept"
                           :multiple="multiple">
                    <div :title="name" @click="$refs.fileInput.click"
                         :class="['border-primary rounded flex-center-center', fileSelected ? 'btn-selected-file' : '' ]"
                         style="height:35px">
                        <div v-if="fileSelected" class="flex-center-center text-dark">{{ truncateText(name, 20) }}</div>
                        <div v-else>
                            <div v-if="placeholder">{{ placeholder }}</div>
                            <div v-else>انتخاب فایل</div>
                        </div>
                    </div>
                    <p v-if="w && h" class="text-primary"><small class="text-muted">فایل باید در ابعاد {{ w }} در
                        {{ h }} باشد</small></p>
                </div>
                <!--todo: <img class="col" style="max-height: 100px;max-width: 100px" :src="modelValue" />-->
            </div>
            <div v-if="file.length !== 0 || oldFiles.length !==0" class="d-flex flex-wrap pb-1" style="gap: 8px;">
                <div v-for="(item, index) in oldFiles" class="d-flex align-items-center"
                     style="background-color: #dedede;padding: 2px 8px;border-radius: 10px;">
                    <a target="_blank" :href="item.file">فایل {{ 1 + index }}</a>
                    <div @click="$emit('onRemove', item)" style="padding: 6px;">
                        <i class="fas fa-times text-danger"></i>
                    </div>
                </div>

                <div v-for="(item, index) in file" class="d-flex align-items-center"
                     style="background-color: #dedede;padding: 2px 8px;border-radius: 10px;">
                    <a target="_blank" :href="getImageHref(item)">فایل {{ oldFiles.length + index + 1 }}</a>
                    <div @click="removeFile(index)" style="padding: 6px;">
                        <i class="fas fa-times text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import imageCompression from 'browser-image-compression';

export default {
    props: {
//            modelValue: [String,File], //todo:
        title: String,
        placeholder: String,
        id: String,
        required: String,
        multiple: Boolean,
        w: [String, Number],
        h: [String, Number],
        accept: String,
        colum: {
            type: Boolean,
            default: true,
        },
        val: {
            default: null,
        },
        removeKey: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            fileSelected: false,
            file: '',
            name: '',
            oldFiles: [],
        }
    },
    model: {
        prop: 'val',
        event: 'input',
    },
    watch: {
        val() {
            this.setValues();
        }
    },
    methods: {
        onchange(event) {
            this.file = Array.from(event.target.files);

            this.file.map(async (item, index) => {
                if (item.type.startsWith("image/")) {
                    this.file[index] = await this.compress(item);
                }
            });

            this.setName();
            this.emitFiles();

        },
        emitFiles() {
            if (this.multiple) {
                this.$emit('update:modelValue', this.file);
            } else {
                if (this.file[0]) {
                    this.$emit('update:modelValue', this.file[0]);
                } else {
                    this.$emit('update:modelValue', '');
                }
            }
        },
        getImageHref(file) {
            return URL.createObjectURL(file);
        },
        setName() {
            if (this.file != "" && this.file != null && this.file != undefined && this.file.length !== 0)
                this.fileSelected = true;
            else
                this.fileSelected = false;

            const names = [];
            this.file.map(item => {
                names.push(item.name);
            });

            this.name = names.join(',');
        },
        removeFile(index) {
            confirm2('آیا از حذف این فایل اطمینان دارید؟', "حذف فایل", () => {
                this.file.splice(index, 1);
                this.setName();
                this.emitFiles();
            });
        },
        setValues() {
            if (this.val) {
                this.oldFiles = this.val;
            }
        },
        truncateText(text, maxLength = 50) {
            if (text.length <= maxLength) {
                return text;
            }
            return text.slice(0, maxLength) + '...';
        },
        async compress(file) {
            try {
                const compressedBlob = await imageCompression(file, {
                    useWebWorker: true,
                });

                return new File([compressedBlob], file.name, {
                    type: compressedBlob.type,
                    lastModified: Date.now(),
                });
            } catch (error) {
                console.error("خطا در فشرده‌سازی:", error);
                return file;
            }
        }
    },
    mounted() {
        this.setValues();
    }
}
</script>

<style>
.btn-selected-file {
    background-color: #99d9b4 !important;
}
</style>
