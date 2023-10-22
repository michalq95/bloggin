<template>
    <div class="rounded-sm ml-4 border-solid border-white border-2">
        <div class="flex justify-between items-center p-2">
            <div class="flex p-2">
                <img
                    v-if="comment.image"
                    :src="comment.image.url"
                    class="w-8 object-cover"
                />
                <div class="w-8" v-else></div>
            </div>
            <div class="flex font-bold text-slate-200">
                {{ comment.title }} {{ comment.id }}
            </div>
            <div class="flex">{{ comment.created_at }}</div>
            <!-- {{ comment }} -->
            <!--   {{ comment.id }} -->
        </div>
        <div class="text-left p-2">
            {{ comment.description }}
        </div>
        <div class="flex justify-between items-center p-2">
            <button
                v-if="comment.comments_meta.has_next_page"
                class="flex mt-1 focus:ring-indigo-500 p-3 focus:border-indigo-500 text-center shadow-sm sm:text-sm border-gray-300 rounded-r-md text-slate-100 bg-slate-800"
                @click="loadMoreComments"
            >
                Load more comments
            </button>
            <div class="flex" v-else></div>
            <button
                class="flex mt-1 focus:ring-indigo-500 p-3 focus:border-indigo-500 text-center shadow-sm sm:text-sm border-gray-300 rounded-r-md text-slate-100 bg-slate-800"
                @click="replying = !replying"
            >
                Reply
            </button>
        </div>
        <NewComment
            v-if="replying"
            @close="replying = false"
            :parent="comment"
            :parentType="'comment'"
        ></NewComment>
        <Comment
            v-for="nestedComment in comment.comments"
            :comment="nestedComment"
        >
        </Comment>
    </div>
</template>

<script setup>
import { ref } from "vue";

import { getMoreComments } from "../service";
import NewComment from "./NewComment.vue";
const replying = ref(false);
const props = defineProps({
    comment: Object,
});
async function loadMoreComments() {
    const data = await getMoreComments({
        model: "comment",
        id: props.comment.id,
        page: props.comment.comments_meta.current_page,
    });
    props.comment.comments = [...props.comment.comments, ...data.comments];
    props.comment.comments_meta = data.comments_meta;
}
</script>
