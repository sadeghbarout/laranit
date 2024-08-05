<template>
    <div >

        <div>
            <div v-if="type == 'checkbox' ">
                <div class="row mt-2 mb-3">
                    <div class="col-6" v-html="title"></div>
                    <div class="col-6">
                        <input :type="type" :id="id" :name="name" :value="val" @click="$emit('input', $event.target.value)">
                    </div>
                </div>
            </div>
            <div v-else>

                <div class="form-group w-100" :class="{'row': !colum}">
                    <div v-if="title" class="d-flex justify-content-start text-gray-dark fw-700" :class="{'col-4 align-items-center': !colum}" style="padding-bottom: 4px;">
                        <label :for="id" v-html="title"></label>
                    </div>
                    <div :class="{'col-8': !colum}">
                        <fieldset v-if="type != 'number'" :class="[icon==undefined ? '' : 'form-group position-relative has-icon-left input-divider-left' ]">

                            <!--  Button -->
                            <input v-if="type == 'submit' " type="submit" :class="['form-control '+customClass]" :value="val" :required="required">

                            <!--  Text -->
                            <input v-else :type="type" :id="id" v-model="inputVal" :placeholder="placeholder!=undefined? placeholder : ''  "
                                   :class="['form-control '+customClass]" :required="required" :disabled="disabled"  @input="validateNumber">

                            <i v-if="showPasswordIcon " @click="showPassword()" class="fa fa-eye cursor-pointer" style="position:absolute;left:-3px;top:12px"></i>

                            <div class="form-control-position" v-if="type != 'submit' && icon!=undefined">
                                <i :class="icon"></i>
                            </div>
                        </fieldset>


                        <!--  Number -->
                        <div :class="['input-group bootstrap-touchspin w-100 px-0 mx-0', disabled  == true ? 'disabled' : '' ]" v-if="type=='number'">
                            <span class="input-group-btn input-group-prepend bootstrap-touchspin-injected">
                                <button class="btn btn-primary bootstrap-touchspin-down" type="button" @click="numberOperation('dec')">-</button>
                            </span>
                            <input type="number" :step="numberStep" class="touchspin form-control" min="0" v-model="inputVal"  :placeholder="placeholder? placeholder : ''  "
                                   :required="required">
                            <span class="input-group-btn input-group-append bootstrap-touchspin-injected">
                                <button class="btn btn-primary bootstrap-touchspin-up" type="button" @click="numberOperation('inc')">+</button>
                            </span>
                        </div>

                        <p  v-if="inputHint" class="mt-1 text-warning" >{{inputHint}}</p>



                    </div>
                </div>
            </div>
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
        autocomplete: String,
        maxLength:String,
        icon:String,
        customClass:String,
        inputHint:String,
        numberStep:{
            type:Number,
            default:1,
        },
        minNumber:{
            type:Number,
            default:0,
        },
        maxNumber:Number,
        readOnly:{
            type:Boolean,
            default:false,
        },
        required:{
            type:Boolean,
            default:false,
        },
        showPasswordIcon:{
            type:Boolean,
            default:false,
        },
        disabled:{
            type:Boolean,
            default:false,
        },
        colum:{
            type:Boolean,
            default:true,
        },
        min:[Number,String],
        max:[Number,String],
        justNumber:{
            type:Boolean,
            default:false,
        },
        separateNumber:{
            type:Boolean,
            default:false,
        },
    },
    data(){
        return {
        }
    },
    methods:{

        numberOperation(op){
            if(op == 'inc'){
                if(this.maxNumber==undefined || this.inputVal<this.maxNumber)
                    this.inputVal++;
            }
            else if(op == 'dec'){
                if(this.inputVal > this.minNumber){
                    this.inputVal--;
                }
            }
        },

        showPassword(){
            if(this.type == 'password'){
                this.type = 'text';
            }
            else{
                this.type = 'password';
            }
        },

        validateNumber(event) {
            if(this.justNumber===true){
                const persianNumbers = '۰۱۲۳۴۵۶۷۸۹';
                const arabicNumbers = '٠١٢٣٤٥٦٧٨٩';

                let value = event.target.value;
                let newValue = '';

                // Loop through each character in the input value
                for (let char of value) {
                    // Convert Persian and Arabic numerals to their Arabic counterparts
                    if (persianNumbers.includes(char)) {
                        char = persianNumbers.indexOf(char).toString();
                    } else if (arabicNumbers.includes(char)) {
                        char = arabicNumbers.indexOf(char).toString();
                    }

                    // Keep only numerical characters
                    if (/^[0-9]$/.test(char)) {
                        newValue += char;
                    }
                }

                // Format the number with commas every three digits
                if (newValue && this.separateNumber===true) {
                    newValue = newValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                // Update the input value
                this.inputVal = newValue;
            }
        }
    }
    ,
    mounted() {
        this.inputVal=this.val==undefined?'':this.val;

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
