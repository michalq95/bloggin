<template>
    <div>
        <div class="cursor-pointer">^</div>
        <div>{{ score }}</div>
        <div class="cursor-pointer">V</div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
const props = defineProps({
    score: Number,
    id: Number,
    type: String,
});
</script>
