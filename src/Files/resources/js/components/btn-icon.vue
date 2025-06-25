<script setup>
import {ref} from "vue";

const isHover = ref(false);
</script>

<template>
    <div @mouseenter="isHover=true" @mouseleave="isHover=false" style="font-size: 18px;padding: 10px;transition-duration: 300ms;" class="cursor-pointer" :class="{
        'text-primary': isHover,
    }">
        <slot/>
    </div>
</template>
