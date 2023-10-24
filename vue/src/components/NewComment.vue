<template>
    <div
        class="justify-between items-center py-3 px-5 shadow-md dark:bg-sky-700"
    >
        <div>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close')"
            >
                X
            </button>

            <button class="p-2 mx-2 bg-blue-500 rounded-2xl" @click="confirm()">
                Add new comment
            </button>
        </div>

        <label
            for="title"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >Title</label
        >
        <textarea
            :disabled="editing"
            v-model="newCommentModel.title"
            rows="1"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Title"
        ></textarea>

        <label
            for="message"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >Your message</label
        >
        {{ newCommentModel.description }}
        <!-- <textarea
            v-model="newCommentModel.description"
            rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Write your thoughts here..."
        ></textarea> -->
        <div class="bg-white">
            <QuillEditor
                theme="snow"
                v-model:content="newCommentModel.description"
                :contentType="'html'"
                toolbar="essential"
            />
        </div>
        <div>
            <label
                @click.stop
                class="cursor-pointer flex h-12 w-12 shrink-0"
                for="file-input"
            >
                <img
                    v-if="image"
                    :src="image"
                    class="rounded-sm h-12 !w-12 min-w-12 object-cover"
                />
                <span
                    v-else
                    class="items-center justify-center h-12 w-12 min-w-12 rounded-full bg-gray-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12 text-gray-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
            </label>
            <input
                id="file-input"
                type="file"
                accept="image/*"
                @change="onImageChoose"
                style="display: none"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

import { saveComment, putComment } from "../service";
const emit = defineEmits(["close", "newComment", "alteredComment"]);
const props = defineProps({
    parentId: Number,
    parentType: String,
    editing: Boolean,
    editedComment: Object,
});
const image = ref(null);
const newCommentModel = ref({
    title: "",
    description: "",
    image: "",
});

onMounted(() => {
    if (props.editing && props.editedComment) {
        newCommentModel.value.title = props.editedComment.title;
        newCommentModel.value.description = props.editedComment.description;
        image.value = props.editedComment.image.url;
    }
});

function confirm() {
    if (!props.editing) newComment();
    else editComment();
}

async function newComment() {
    const formData = new FormData();
    for (const field in newCommentModel.value) {
        formData.append(field, newCommentModel.value[field]);
    }
    formData.append("image[]", newCommentModel.value.image);
    const data = await saveComment({
        model: props.parentType,
        id: props.parentId,
        formData,
    });
    emit("newComment", data);
    newCommentModel.value = {
        title: "",
        description: "",
        image: "",
    };
    emit("close");
}

async function editComment() {
    const formData = new FormData();
    formData.append("description", newCommentModel.value.description);
    formData.append("image[]", newCommentModel.value.image);
    formData.append("_method", "PUT");
    const data = await putComment({
        model: props.parentType,
        id: props.parentId,
        formData,
    });
    emit("alteredComment", { ...data, parent: undefined });
    newCommentModel.value = {
        title: "",
        description: "",
        image: "",
    };
    emit("close");
}

function onImageChoose(ev) {
    console.log(ev);
    newCommentModel.value.image = ev.target.files[0];
    image.value = URL.createObjectURL(ev.target.files[0]);
}
</script>
