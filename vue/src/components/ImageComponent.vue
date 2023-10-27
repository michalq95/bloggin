<template>
    <div @click="largeImage = !largeImage">
        <img
            :style="[largeImage ? { width: '100%' } : { width: `${width}px` }]"
            class="h-48 object-cover md:h-full"
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
