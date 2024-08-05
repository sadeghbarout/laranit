<template>

    <fieldset class="form-label-group form-group position-relative has-icon-left">
        <input class="form-control" :type="type" :id="id" v-model="inputVal" :placeholder="placeholder" style="margin-top:10px"  @input="validateNumber">
        <div class="form-control-position" >
            <i class="feather icon-user" style="top:20px"></i>
            <i class="feather icon-eye " style="cursor: pointer;top:10px" v-if="showPasswordIcon " @click="showPassword()"></i>
            <!--<i class="feather icon-eye" style="top:10px"></i>-->

        </div>
        <label :for="id"  v-html="title" style="font-size: 14px  " class="mb-5"></label>
    </fieldset>

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
            icon:String,
            showPasswordIcon:{
                type:Boolean,
                default:false,
            },
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
