<template>
    <div class="modal fade" :id="id">
        <div :class="['modal-dialog',modalSize]">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h4 class="modal-title" v-html="title"></h4>
                    <button type="button" :id="'closeModalBtn'+id" class="close float-right" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <div class="modal-body text-left">
                    <slot></slot>
                </div>



            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            title : String,
            id : String,
            modalSize : String,
        },
        methods:{

        },
        mounted(){
        }
    }
</script>
