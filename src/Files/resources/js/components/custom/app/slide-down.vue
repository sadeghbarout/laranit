<template>
    <div>
        <div @click="show=!show">
            <slot name="btn"/>
        </div>
        <Transition>
            <div v-if="show" style="overflow: hidden;">
                <slot/>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    props:{
        defaultVisible: {
            type: Boolean,
            default: false
        }
    },
    data(){
        return{
            show: true
        }
    },
    mounted() {
        this.show = this.defaultVisible
    }
}
</script>

<style>
.v-enter-active,
.v-leave-active {
    transition: max-height 300ms ease;
    max-height: 1500px;
}
.v-enter-from,
.v-leave-to {
    max-height: 0;
}
.nav-item{
    padding-right: 18px !important;
}
.nav-item a i{
    font-size: 18px !important;
}
</style>
