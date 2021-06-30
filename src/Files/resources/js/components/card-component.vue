<template>

    <div :class="['card '+customClass]">
        <div class="card-header bg-dark">
            <div class="card-title  text-right text-warning w-100 flex-center-right" v-if="title != undefined" v-html="title"></div>
            <slot name="header"></slot>
        </div>
        <div class="card-body">
            <div class="row">
                <slot></slot>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['title','customClass','bg'],
    }
</script>
