<template>
    <div v-if="isOpen" :id="id" class="d-flex align-items-center custom-modal-content px-1" @click.self="close(true)">
        <div class="w-100" @click.self="close(true)">
            <div class="custom-modal-box bg-white mx-auto" :style="`min-height: 150px; max-width: ${maxWidth};`">
                <h4 v-if="title" class="text-center pt-1">{{title}}</h4>
                <div>
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            default: null
        },
        title: {
            type: String,
            default: null
        },
        maxWidth: {
            type: String,
            default: '400px'
        },
        closeable: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isOpen: false,
        }
    },
    watch: {
        '$modal.openId'(newValue){
            if(newValue === this.id){
                this.open();
            }
        },
        '$modal.closeId'(newValue){
            if(newValue === this.id){
                this.close();
            }
        }
    },
    methods: {
        open(){
            this.isOpen = true;
        },
        close(isUserReact = false){
            if(!this.closeable && isUserReact)
                return;


            this.$emit('onClose');

            this.isOpen = false;
        }
    }
}
</script>

<style>
.custom-modal-content {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 999999999;
    background-color: rgba(0, 0, 0, 0.5);
    width: 100%;
    height: 100vh;
}

.custom-modal-box{
    border-radius: 4px;
}
</style>
