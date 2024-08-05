<template>
    <div>
        <div class="form-group" >
            <fieldset class="form-group w-100" :class="{'row': !colum}">
                <div v-if="title" class="d-flex justify-content-start text-gray-dark fw-700" :class="{'col-4 align-items-center': !colum}" style="padding-bottom: 4px;">
                    <label v-text="title"></label>
                </div>
                <div :class="{'col-8': !colum}">
                    <select  class="form-control" :id="id" :name="name" v-model="inputVal" :required="required">
                        <option v-if="emptyOption !== undefined" value="">انتخاب کنید</option>
                        <option v-for="opt in options" v-text="opt[0]" :value="opt[1]" :selected="opt[1] == selectedValue" ></option>
                    </select>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        options: Array,
        modelValue: [String, Number],
        title: String,
        val: [String, Number],
        id: [String, Number],
        name: String,
        required: String,
        emptyOption: String,
        selectedValue: String,
        colum: {
            type: Boolean,
            default: true,
        },
    },
    watch: {
        options(val) {
            if (this.inputVal == '' && this.selectedValue === undefined && val.length > 0) {
                this.inputVal = val[0][1]
            }

        }
    },

    mounted() {
        this.inputVal = this.val == undefined ? '' : this.val;
    },
    computed: {
        inputVal: {
            get() {
                return this.modelValue;
            },
            set(inputVal) {
                this.$emit('update:modelValue', inputVal);
            }
        }
    },
}
</script>
