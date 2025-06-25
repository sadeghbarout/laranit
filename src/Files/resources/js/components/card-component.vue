<template>
    <div :class="['card ' + customClasses]">
        <div :class="['card-header', headerBg]">
            <template v-if="toggle">
                <button type="button" @click="isOpen = !isOpen" class="toggle-btn">
                    <i v-if="isOpen" class="fas fa-minus"></i>
                    <i v-else class="fas fa-plus"></i>
                </button>
                <div style="height: 32px;"></div>
            </template>

            <div class="card-title" v-if="title" v-html="title"></div>
            <slot name="header"></slot>
        </div>
        <transition name="fade">
            <div v-if="isOpen" class="card-body">
                <div class="row">
                    <slot></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        customClasses: String,
        headerBg: {
            default: '',
            type: String,
        },
        toggle: {
            default: false,
            type: Boolean,
        },
        open: {
            default: true,
            type: Boolean,
        }
    },
    data() {
        return {
            isOpen: false,
        };
    },
    mounted() {
        if (!this.toggle) {
            this.isOpen = true;
        }

        if (this.open) {
            this.isOpen = true;
        }
    }
};
</script>

<style>
.toggle-btn {
    position: absolute;
    top: 16px;
    left: 16px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    margin-right: 10px;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.15s ease-in-out, max-height 0.15s ease-in-out;
    max-height: 500px;
    overflow: hidden;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
    max-height: 0;
}
</style>
