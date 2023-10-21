<template>
    <!-- {{ posts[0] }} -->
    <div v-if="!posts">Loading...</div>

    <div v-else>
        <div class="my-2 w-11/12">
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
            <div class="max-w-4xl px-10 py-6 bg-white rounded-lg shadow-md">
                <router-link
                    class="text-2xl text-gray-700 font-bold hover:underline"
                    :to="{ name: 'BlogPost', params: { id: post.id } }"
                    >{{ post.title }}</router-link
                >
                <div class="flex justify-between items-center">
                    <img
                        v-if="post.image"
                        class="mx-4 w-64 object-cover rounded-sm hidden sm:block"
                        :src="post.image.url"
                        alt="avatar"
                    />
                    <span>
                        <div class="font-light text-gray-600">
                            {{ post.created_at }}
                        </div>
                        <div
                            class="px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded"
                        >
                            <button
                                v-for="tag in post.tags"
                                :key="tag"
                                class="hover:bg-gray-500 mx-1 px-1"
                                @click="addTag(tag)"
                            >
                                {{ tag }}
                            </button>
                        </div>
                    </span>
                </div>

                <div class="mt-2">
                    <p class="mt-2 text-gray-600">
                        {{ post.description.slice(0, 100) }}
                    </p>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <router-link
                        class="text-blue-500 hover:underline"
                        :to="{ name: 'BlogPost', params: { id: post.id } }"
                        >Read more</router-link
                    >
                    <div class="flex items-center">
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
                    </div>
                </div>
            </div>
        </div>
        <v-pagination
            v-model="meta.current_page"
            :length="meta.last_page"
            prev-icon="mdi-menu-left"
            next-icon="mdi-menu-right"
        ></v-pagination>
    </div>
</template>
<script setup>
import { ref, onMounted, watch } from "vue";
import { getPosts } from "../service";

const posts = ref([]);
const meta = ref({});
const keywords = ref("");
const keywordsConfirmed = ref("");
onMounted(async () => {
    const data = await getPosts();
    posts.value = data.data;
    meta.value = data.meta;
});
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
        keyword: keywords.value.split(" ").join(","),
    });
    posts.value = data.data;
    meta.value = data.meta;
    keywordsConfirmed.value = keywords.value;
}

function addTag(tag) {
    if (!keywords.value.split(" ").includes((el) => el == tag))
        keywords.value += ` ${tag}`;
}
</script>
