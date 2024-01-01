<template>
    <div v-if="!post">Loading...</div>
    <div
        v-else
        class="max-w-5xl px-10 py-6 dark:bg-slate-600 rounded-lg shadow-md"
    >
        <div class="flex justify-between items-center">
            <span class="font-light text-gray-800">{{ post.created_at }}</span>
            <div
                v-if="post.tags && post.tags.length > 0"
                class="px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded"
            >
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
            <div class="flex">
                <ImageComponent
                    v-if="post.image[0]"
                    :imageUrl="post.image[0].url"
                    :width="96"
                ></ImageComponent>
                <div class="w-16" v-else></div>
            </div>
            <h2 class="text-2xl text-gray-700 dark:text-gray-300 font-bold">
                {{ post.title }}
            </h2>

            <div
                class="m-4 text-gray-600 dark:text-gray-300 text-left"
                v-html="post.description"
            ></div>
            <article class="m-4 text-gray-600 dark:text-gray-300 text-left">
                <div v-for="content in post.content">
                    <div v-if="content.text" v-html="content.text"></div>
                    <div
                        class="flex"
                        v-if="content.upload && content.upload.image"
                    >
                        <ImageComponent
                            :imageUrl="content.upload.image.url"
                            :width="96"
                        ></ImageComponent>
                        <div
                            class="my-1 cursor-pointer text-teal-200"
                            @click="downloadFile(content.upload.id)"
                        >
                            Download
                            {{ content.upload.filename }}
                            {{ useHumanReadableFileSize(content.upload.size) }}
                        </div>
                    </div>
                </div>
            </article>
            <div
                v-if="post.uploads && post.uploads.length > 0"
                class="text-left flex flex-col"
            >
                <div v-if="isLoggedIn">
                    Downloads:
                    <div
                        v-for="upload in post.uploads"
                        :key="upload.id"
                        class="flex my-1 cursor-pointer"
                        @click="downloadFile(upload.id)"
                    >
                        <img
                            v-if="upload.image"
                            :src="upload.image.url"
                            alt="upload miniature"
                            class="h-12 w-12 rounded-lg object-cover"
                        />

                        <img
                            v-else
                            class="h-12 w-12 rounded-lg object-cover"
                            src="../assets/placeholder.jpg"
                            alt="placeholder_avatar"
                        />
                        {{ upload.filename }}
                        {{ useHumanReadableFileSize(upload.size) }}
                    </div>
                </div>
                <div v-else>Log in to download files</div>
            </div>
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
            <strong class="text-left text-white m-3">Comments</strong>
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
                v-if="post.comments.length > 0"
                v-for="comment in post.comments"
                :comment="comment"
            ></Comment>
            <div class="text-left" v-else>No comments so far</div>
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
import { useHumanReadableFileSize } from "../composables/readableFileSize";
import { getPost, getMoreComments, downloadUploadedFile } from "../service";
import store from "../store";
import ImageComponent from "../components/ImageComponent.vue";
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

    const filteredComments = data.data.comments.filter(
        (comment) => !doesCommentExist(post.value.comments, comment.id)
    );
    post.value.comments = [...post.value.comments, ...filteredComments];
    post.value.comments_meta = data.data.comments_meta;
}

function addNewlyAddedComment(data) {
    data = data.data;
    post.value.comments.unshift({ ...data, parent: undefined });
}

async function downloadFile(fileId) {
    await downloadUploadedFile(fileId);
}
</script>
