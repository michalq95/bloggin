<template>
    <div v-if="!post">Loading...</div>
    <div v-else class="max-w-4xl px-10 py-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <span class="font-light text-gray-600">{{ post.created_at }}</span>
            <div class="px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded">
                <button
                    v-for="tag in post.tags"
                    :key="tag"
                    class="hover:bg-gray-500 mx-1 px-1"
                    href="#"
                >
                    {{ tag }}
                </button>
            </div>
        </div>
        <div class="mt-2">
            <h2 class="text-2xl text-gray-700 font-bold">
                {{ post.title }}
            </h2>
            <p class="mt-2 text-gray-600">
                {{ post.description }}
            </p>
        </div>
        <div class="flex justify-between items-center mt-4">
            <span class="font-light text-gray-600"
                ><span v-if="post.updated_at != post.created_at"
                    >updated:{{ post.updated_at }}</span
                ></span
            >
            <div>
                <a class="flex items-center" href="#">
                    <img
                        v-if="post.user.image"
                        class="mx-4 w-10 h-10 object-cover rounded-full hidden sm:block"
                        :src="post.user.image"
                        alt="avatar"
                    />
                    <img
                        v-else
                        class="mx-4 w-10 h-10 object-cover rounded-full hidden sm:block"
                        src="../assets/placeholder.jpg"
                        alt="placeholder_avatar"
                    />
                    <h1 class="text-gray-700 font-bold hover:underline">
                        {{ post.user.name }}
                    </h1>
                </a>
            </div>
        </div>
        <div class="rounded-md p-2 dark:bg-sky-900 bg-sky-300">
            <strong class="text-left text-white">Comments:</strong>
            <ul>
                <Comment
                    v-for="comment in post.comments"
                    :comment="comment"
                ></Comment>
            </ul>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { getPost } from "../service";
import Comment from "../components/Comment.vue";
const route = useRoute();

const post = ref(null);
onMounted(async () => {
    if (route.params.id) {
        const data = await getPost(route.params.id);
        post.value = data.data;
    }
});
</script>
