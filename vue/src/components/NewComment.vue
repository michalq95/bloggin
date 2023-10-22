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

            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="newComment()"
            >
                Add new comment
            </button>
        </div>

        <label
            for="title"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >Title</label
        >
        <textarea
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
        <textarea
            v-model="newCommentModel.description"
            rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Write your thoughts here..."
        ></textarea>

        <div>
            <label
                @click.stop
                class="cursor-pointer flex h-12 w-12 shrink-0"
                for="file-input"
            >
                <img
                    v-if="parent.image"
                    :src="parent.image"
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
import { ref } from "vue";

import { saveComment } from "../service";
const emit = defineEmits(["close"]);
const props = defineProps({
    parent: Object,
    parentType: String,
});

const newCommentModel = ref({
    title: "",
    description: "",
    image: "",
});
function onImageChoose(ev) {
    newCommentModel.value.image = ev.target.files[0];
    props.parent.image = URL.createObjectURL(ev.target.files[0]);
}
async function newComment() {
    const formData = new FormData();
    for (const field in newCommentModel.value) {
        formData.append(field, newCommentModel.value[field]);
    }
    const data = await saveComment({
        model: props.parentType,
        id: props.parent.id,
        formData,
    });

    newCommentModel.value = {
        title: "",
        description: "",
        image: "",
    };

    emit("close");
}
</script>
