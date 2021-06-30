<template>
    <div class="col-12 p-0">
        <div class="form-group">
            <fieldset class="form-group row mb-2 flex-center-center">
                <div v-if="title != undefined" class="col-md-4">
                    <span v-text="title"></span>
                </div>
                <div :class="['p-0',title != undefined? 'col-md-8': 'col-md-12' ]">
                    <select  class="form-control" :id="id" :name="name" @input="$emit('input', $event.target.value)" :required="required!=undefined">
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
        props: ['options', 'title', 'val', 'id', 'name', 'selectedValue','required','emptyOption'],
        model: {
            prop: 'val',
            event: 'input'
        },
        watch:{
            options(val){
                if(this.selectedValue===undefined && val.length>0){
                    this.$emit('input', val[0][1])
                }


            }
        }
    }
</script>
