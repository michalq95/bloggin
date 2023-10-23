<template>
    <div v-if="!post">Loading...</div>
    <div
        v-else
        class="max-w-4xl px-10 py-6 dark:bg-slate-600 rounded-lg shadow-md"
    >
        <div class="flex justify-between items-center">
            <span class="font-light text-gray-800">{{ post.created_at }}</span>
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
            <h2 class="text-2xl text-gray-700 dark:text-gray-300 font-bold">
                {{ post.title }}
            </h2>
            <p class="m-4 text-gray-600 dark:text-gray-300 text-left">
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
        <div class="rounded-md p-2 dark:bg-slate-700 bg-sky-300">
            <strong class="text-left text-black m-3">Comments:</strong>
            <div class="flex justify-between items-center p-2">
                <button
                    v-if="isLoggedIn"
                    class="flex mt-1 focus:ring-indigo-500 p-3 focus:border-indigo-500 text-center shadow-sm sm:text-sm border-gray-300 rounded-r-md text-slate-100 bg-slate-800"
                    @click="replying = !replying"
                >
                    Reply
                </button>
            </div>
            <NewComment
                v-if="replying"
                @close="replying = false"
                @newComment="addNewlyAddedComment"
                :parentId="post.id"
                :parentType="'post'"
            ></NewComment>
            <Comment
                v-for="comment in post.comments"
                :comment="comment"
            ></Comment>
            <button
                v-if="post.comments_meta.has_next_page"
                class="mt-1 focus:ring-indigo-500 w-1/6 py-3 focus:border-indigo-500 shadow-sm sm:text-sm border-gray-300 rounded-r-md text-slate-100 dark:bg-slate-800"
                @click="loadMoreComments"
            >
                Load more comments
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { getPost, getMoreComments } from "../service";
import store from "../store";
import Comment from "../components/Comment.vue";
import NewComment from "../components/NewComment.vue";

const route = useRoute();

const post = ref(null);
const replying = ref(false);

let isLoggedIn = computed(() => store.getters.isLoggedIn);

onMounted(async () => {
    if (route.params.id) {
        const data = await getPost(route.params.id);
        post.value = data.data;
    }
});

const doesCommentExist = (comments, id) => {
    return comments.some((comment) => comment.id === id);
};

async function loadMoreComments() {
    const data = await getMoreComments({
        model: "post",
        id: post.value.id,
        page: post.value.comments_meta.page + 1,
    });

    const filteredComments = data.comments.filter(
        (comment) => !doesCommentExist(post.value.comments, comment.id)
    );
    post.value.comments = [...post.value.comments, ...filteredComments];
    post.value.comments_meta = data.comments_meta;
}

function addNewlyAddedComment(data) {
    post.value.comments.unshift({ ...data, parent: undefined });
}
</script>
