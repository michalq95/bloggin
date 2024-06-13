<template>
    <div class="flex flex-col max-w-2xl m-auto">
        <img :src="image.image" />
        {{ image.answer }}
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { getRandomImage } from "@/service";

const image = ref({
    image: "",
    answer: "",
});

onMounted(async () => {
    const data = await getRandomImage();
    image.value = data.data;
});
</script>
