<template>
    <div >

        <div class="col-12 p-0">
            <div v-if="type == 'checkbox' ">
                <div class="row">
                    <div class="col-6" v-html="title"></div>
                    <div class="col-6">
                        <input :type="type" :id="id" :name="name" :value="val" @click="$emit('input', $event.target.value)">
                    </div>
                </div>
            </div>
            <div v-else>

                <div class="form-group row flex-center-center">
                    <div :class="title!=undefined? 'col-md-4' : '' ">
                        <span :for="id" v-html="title">نام</span>
                    </div>
                    <div :class="[' p-0',title!=undefined? 'col-md-8' : 'col-md-12' ]">

                        <fieldset v-if="type != 'number'" :class="[icon==undefined ? '' : '  form-group position-relative has-icon-left input-divider-left' ]">
                            <input :type="type" :id="id" :name="name" :value="val" :placeholder="placeholder!=undefined? placeholder : ''  " @input="$emit('input', $event.target.value)"
                                :class="['form-control '+classes]" :required="required!=undefined">

                                <i v-if="showPasswordIcon !== undefined " @click="showPassword()" class="fa fa-eye cursor-pointer" style="position:absolute;left:-3;top:12px"></i>

                            <div class="form-control-position" v-if="type != 'submit' && icon!=undefined">
                                <i :class="icon"></i>
                            </div>
                        </fieldset>


                         <div class="input-group bootstrap-touchspin" v-if="type=='number'" style="direction:ltr">
                            <span class="input-group-btn input-group-prepend bootstrap-touchspin-injected">
                                <button class="btn btn-primary bootstrap-touchspin-down" type="button" @click="numberOperation('dec')">-</button>
                            </span>
                            <input type="number" :step="numberStep" class="touchspin form-control" min="0"  :name="name" :value="inputValue == '' || inputValue == null ?0: inputValue" :placeholder="placeholder!=undefined? placeholder : ''  " @input="$emit('input', $event.target.value)"
                                :required="required!=undefined">
                            <span class="input-group-btn input-group-append bootstrap-touchspin-injected">
                                <button class="btn btn-primary bootstrap-touchspin-up" type="button" @click="numberOperation('inc')">+</button>
                            </span>
                        </div>








                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'val', 'id', 'name','type','required','classes','checked','inputHint','placeholder','icon','showPasswordIcon','numberStep'],
        model: {
            prop: 'val',
            event: 'input'
        },
        data(){
            return{
                inputValue : '',
            }
        },
        methods: {
            // clearInput(e){
            //     this.inputValue = '';
            //     this.$emit('input', this.inputValue)
            // },

            numberOperation(op){
                if(op == 'inc'){
                    this.inputValue++;
                }
                else if(op == 'dec'){
                    if(this.inputValue >= 1){
                        this.inputValue--;
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
        },

        mounted(){

        },

        watch: {
            val:{
                immediate:true,
                handler (val, oldVal) {
                    this.inputValue = this.val;
                    this.$emit('input', this.inputValue)
                },
            },
            inputValue:{
                immediate:true,
                handler (val, oldVal) {
                    this.$emit('input', this.inputValue)
                },
            }
        },
    }
</script>
