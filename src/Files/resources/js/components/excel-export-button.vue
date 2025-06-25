<script setup>
import {ref, watch} from "vue";

const props = defineProps({
    loading: {
        type: Boolean,
        default: false,
    }
});


const emit = defineEmits(['onExport']);
const excelModal = ref();
const isOpenModal = ref(false);

function fetchData() {
    isOpenModal.value = true
    excelModal.value?.open();
    emit('onExport', 1)
}


watch(() => props.loading, () => {
    if (!props.loading && isOpenModal.value) {
        excelModal.value?.close();
        isOpenModal.value = false
    }
});
</script>

<template>
    <div>
        <btn-icon @click="fetchData(1, 0)" title="خروجی اکسل">
            <i class="fas fa-file-excel"></i>
        </btn-icon>

        <modal title="خروجی اکسل" ref="excelModal" :closeable="false">
            <div class="px-1 pb-1 pt-1">
                <div class="alert alert-success text-center">در حال ساخت خروجی اکسل هستیم. این فرایند ممکن است تا ۱ دقیقه زمان ببرد.
                    لطفاً چند لحظه صبر کنید.
                </div>

                <div class="d-flex justify-content-center">
                    <div class="lds-ripple"><div></div><div></div></div>
                </div>
            </div>
        </modal>
    </div>
</template>

<style>
.lds-ripple,
.lds-ripple div {
    box-sizing: border-box;
}
.lds-ripple {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}
.lds-ripple div {
    position: absolute;
    border: 4px solid currentColor;
    opacity: 1;
    border-radius: 50%;
    animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
.lds-ripple div:nth-child(2) {
    animation-delay: -0.5s;
}
@keyframes lds-ripple {
    0% {
        top: 36px;
        left: 36px;
        width: 8px;
        height: 8px;
        opacity: 0;
    }
    4.9% {
        top: 36px;
        left: 36px;
        width: 8px;
        height: 8px;
        opacity: 0;
    }
    5% {
        top: 36px;
        left: 36px;
        width: 8px;
        height: 8px;
        opacity: 1;
    }
    100% {
        top: 0;
        left: 0;
        width: 80px;
        height: 80px;
        opacity: 0;
    }
}
</style>
