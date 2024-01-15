<template>
    <div>
        <a class="flex items-center" href="#">
            <img
                v-if="props.image"
                class="mx-4 w-10 h-10 object-cover rounded-full hidden sm:block"
                :src="props.image"
                alt="avatar"
            />
            <img
                v-else
                class="mx-4 w-10 h-10 object-cover rounded-full hidden sm:block"
                src="../assets/placeholder.jpg"
                alt="placeholder_avatar"
            />
            <h1
                v-if="props.name"
                class="text-gray-700 font-bold hover:underline"
            >
                {{ props.name }}
            </h1>
        </a>
    </div>
</template>

<script setup>
const props = defineProps({
    image: String,
    name: String,
});
</script>
