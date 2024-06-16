<template>
    <div v-if="!posts">Loading...</div>

    <div v-else class="max-w-5xl m-auto">
        <div class="flex justify-end">
            <button
                v-if="store.getters.getPermissions.includes('create post')"
                class="text-slate-100 bg-slate-700 p-2"
            >
                <router-link :to="{ name: 'NewBlogpost' }">
                    Create Post
                </router-link>
            </button>
        </div>
        <div class="my-2">
            <label for="search" class="block text-sm font-medium text-gray-500"
                >Search</label
            >
            <input
                type="text"
                name="search"
                id="search"
                v-model="keywords"
                autocomplete="query string"
                @keyup.enter="search"
                class="mt-1 py-3 w-5/6 dark:bg-slate-300 dark:text-black focus:ring-indigo-500 focus:border-indigo-500 shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
            <button
                autocomplete="search"
                class="mt-1 focus:ring-indigo-500 w-1/6 py-3 focus:border-indigo-500 shadow-sm sm:text-sm border-gray-300 rounded-r-md text-slate-100 bg-slate-700"
                @click="search"
            >
                Search
            </button>
        </div>
        <div v-for="post in posts">
            <BlogPostComponent
                :post="post"
                @elementVoted="elementVoted"
                @addTag="addTag"
            />
        </div>
        <v-pagination
            v-model="meta.current_page"
            :length="meta.last_page"
            prev-icon="mdi-menu-left"
            next-icon="mdi-menu-right"
        ></v-pagination>
        {{ posts[0] }}
    </div>
</template>
<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import BlogPostComponent from "@/components/BlogPostComponent.vue";
import { useStore } from "vuex";
const store = useStore();

import { getPosts } from "@/service";

const posts = ref([]);
const meta = ref({});
const keywords = ref("");
const keywordsConfirmed = ref("");
onMounted(async () => {
    const data = await getPosts();
    posts.value = data.data;
    meta.value = data.meta;
});
function elementVoted(data) {
    const post = posts.value.find((el) => el.id == data.scoreable_id);
    post.score = data.score;
    post.vote = reactive(post.vote || {});
    post.vote.vote = data.value;
}
watch(
    () => meta.value.current_page,
    async (currentPage, old) => {
        if (old) {
            const data = await getPosts({
                page: currentPage,
                keyword: keywordsConfirmed.value.split(" ").join(","),
            });
            posts.value = data.data;
            meta.value = data.meta;
        }
    }
);
async function search() {
    const data = await getPosts({
        keyword: keywords.value.trim().split(" ").join(","),
    });
    posts.value = data.data;
    meta.value = data.meta;
    keywordsConfirmed.value = keywords.value;
}

function addTag(tag) {
    if (!keywords.value.split(" ").includes(tag)) keywords.value += ` ${tag}`;
}
</script>
