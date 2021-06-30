<template>
    <div>
        <div class="row form-group mrml2">

            <div v-if="title!=undefined" :class="title!=undefined? 'col-md-4' : ''" v-text="title"></div>
            <div :class="title!=undefined? 'col-md-8' : 'col-md-12'">
                <date-picker v-model="val" :type="type" :format="format" :locale="locale" :placeholder="placeholder"></date-picker>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['title', 'val', 'id', 'name','type','format','locale','placeholder'],
        model: {
            prop: 'val',
            event: 'input'
        },

        watch: {
            val:{
                immediate:true,
                handler (val, oldVal) {
                    this.inputValue = this.val;
                    this.$emit('input', this.inputValue)
                },
            },
        },

    }
</script>
