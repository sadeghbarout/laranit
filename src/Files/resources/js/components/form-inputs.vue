<template>
    <div ref="inputRef" :class="['form-group row px-1', wrapperClasses]">
        <div class="p-0 col-md-12" >
            <p class="m-0" v-text="title"></p>
            <input dir="auto" class="px-1" :type="type == undefined? 'text' : type "  v-model="displayValue" :step="step" :id="id" :ref="ref" :placeholder="placeholder !== undefined ? placeholder : '' "  :class="['form-control',classes]" :autocomplete="autocomplete" :maxlength="maxLength" :readonly="readOnly == true" :min="min" :max="max" :required="required" :disabled="disabled" @input="validateNumber"
                   :data-rules="disabled != true? rules : ''"
                   @blur="$emit('onBlur')"
            >
        </div>
    </div>
</template>
<script>

export default {
    props: {
        val: [String, Number],
        modelValue: [String, Number],
        title: String,
        type: String,
        placeholder: String,
        id: String,
        classes: String,
        wrapperClasses: String,
        ref: String,
        step: String,
        autocomplete: String,
        maxLength: String,
        disabled: {
            type: Boolean,
            default: false,
        },
        readOnly: {
            type: Boolean,
            default: false,
        },
        required: {
            type: Boolean,
            default: false,
        },
        min: [Number, String],
        max: [Number, String],
        justNumber: {
            type: Boolean,
            default: false,
        },
        separateNumber: {
            type: Boolean,
            default: false,
        },
        rules: {
            type: String,
            default: '',
        },
    },
    data() {
        return {}
    },
    watch: {
        disabled(newVal) {
            this.$nextTick(() => {
                if (newVal) {
                    const elements = this.$refs.inputRef?.querySelectorAll('.validation-errors');
                    elements.forEach(el => {
                        el.remove();
                    });
                }
            });
        }
    },
    methods: {
        validateNumber(event) {
            if (this.justNumber === true) {
                let value = event.target.value;

                const persianNumbers = '۰۱۲۳۴۵۶۷۸۹';
                const arabicNumbers = '٠١٢٣٤٥٦٧٨٩';
                const newValue = value.split('').filter(char => {
                    return /^[0-9]$/.test(char) || persianNumbers.includes(char) || arabicNumbers.includes(char);
                }).join('');

                event.target.value = newValue;
                this.inputVal = newValue;
            } else {
                this.inputVal = event.target.value;
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
        },
        displayValue() {
            if (this.justNumber && this.separateNumber && this.inputVal !== null) {
                return this.inputVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return this.inputVal;
        }
    },
}
</script>
