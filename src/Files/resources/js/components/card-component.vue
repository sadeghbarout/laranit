<template>

    <div :class="['card '+customClasses]">
        <div :class="['card-header', headerBg] ">
            <div class="card-title" v-if="title != undefined" v-html="title"></div>
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
        props: {
            title:String,
            customClasses:String,
            headerBg:{
                default: '',
                type :String,
            }
        },
    }
</script>
