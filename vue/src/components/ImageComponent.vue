<template>
    <div @click="largeImage = !largeImage">
        <img
            :style="[
                largeImage
                    ? { 'max-width': '100%' }
                    : { 'max-width': `${width}px` },
            ]"
            class="object-cover h-full"
            :src="imageUrl"
            :alt="imageUrl"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({
    imageUrl: String,
    width: Number,
});
const largeImage = ref(false);
</script>
