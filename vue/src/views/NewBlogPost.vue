<template>
    <div
        class="justify-between items-center py-3 px-5 my-2 shadow-md dark:bg-slate-600"
    >
        <div class="bg-slate-500 rounded-sm m-7">
            <button
                class="flex p-2 m-2 bg-slate-600 rounded-sm font-extrabold"
                @click="confirm()"
            >
                Add new post
            </button>

            <textarea
                v-model="model.title"
                rows="1"
                class="block p-2.5 w-full my-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Title"
            ></textarea>

            Description
            <div class="bg-gray-700 my-2">
                <QuillEditor
                    theme="snow"
                    v-model:content="model.description"
                    :contentType="'html'"
                    toolbar="essential"
                    rows="5"
                />
            </div>
            <div class="my-2">
                <label
                    @click.stop
                    class="cursor-pointer flex justify-start shrink-0"
                    for="file-input"
                >
                    <img
                        v-if="image"
                        :src="image"
                        class="flex rounded-md h-32 w-32 min-w-12 object-cover"
                    />
                    <span
                        v-else
                        class="flex items-center justify-center h-32 w-32 min-w-12 rounded-md bg-gray-100"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-32 w-32 text-gray-500"
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
                    <button class="flex p-4 font-semibold">Select Image</button>
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
        <!-- <div v-for="(content, i) in contentBlocks" :key="i">
            <div class="bg-gray-700 my-2">
                <div
                    @click="removeContentBlock(content.id)"
                    class="p-2 m-2 bg-slate-500 rounded-sm max-w-fit cursor-pointer"
                >
                    Remove block
                </div>
                <QuillEditor
                    theme="snow"
                    v-model:content="contentBlocks[i].text"
                    :contentType="'html'"
                    toolbar="essential"
                    rows="5"
                />
            </div>
        </div> -->
        <draggable
            class="list"
            v-model="contentBlocks"
            @start="drag = true"
            @end="drag = false"
            item-key="id"
            :disabled="active"
        >
            <template #item="{ element, index }">
                <div class="bg-gray-700 my-2">
                    <div
                        @click="removeContentBlock(element.id)"
                        class="p-2 bg-slate-500 rounded-sm max-w-fit cursor-pointer float-right"
                    >
                        Remove block
                    </div>
                    <div>
                        <div v-if="element.uploadId" class="text-left">
                            {{ element.uploadName }}
                            <img
                                v-if="element.uploadImage"
                                :src="element.uploadImage"
                                class="flex rounded-md h-32 w-32 min-w-12 object-cover"
                            />
                            <img
                                v-else
                                class="mx-2 w-32 h-32 object-cover rounded-sm"
                                src="../assets/placeholder.jpg"
                                alt="placeholder_avatar"
                            />
                        </div>
                        <div
                            class=""
                            :class="active == element.id ? 'bg-slate-200' : ''"
                            v-else
                        >
                            <div
                                @click="setActive(element.id)"
                                class="p-2 m-2 bg-slate-500 rounded-sm max-w-fit cursor-pointer"
                            >
                                Edit text {{ element.id }}
                            </div>
                            <QuillEditor
                                v-if="active == element.id"
                                class="text-black"
                                theme="snow"
                                v-model:content="tempContent"
                                :contentType="'html'"
                                toolbar="essential"
                                rows="5"
                            />
                            <span v-else v-html="element.text"></span>
                        </div>
                        <!-- <QuillEditor
                            v-else
                            theme="snow"
                            v-model:content="contentBlocks[index].text"
                            :contentType="'html'"
                            toolbar="essential"
                            rows="5"
                        /> -->

                        <!-- <QuillEditor
                            v-else
                            theme="snow"
                            v-model:content="element.text"
                            :contentType="'html'"
                            toolbar="essential"
                            rows="5"
                        /> -->
                    </div>
                </div>
            </template>
        </draggable>
        <div class="bg-slate-200 text-black"></div>
        <button
            class="relative p-2 m-2 bg-slate-500 rounded-sm"
            @click="addContentBlock()"
        >
            Add new block
        </button>
        <button
            class="relative p-2 m-2 bg-slate-500 rounded-sm"
            @click="showUploads = !showUploads"
        >
            Show Uploads
        </button>
        <div>
            <UploadPicker
                v-if="showUploads"
                @selectUpload="addUpload"
            ></UploadPicker>
        </div>
        <div class="px-4 py-5 bg-gray-400 dark:bg-gray-800 space-y-6 sm:p-6">
            <h3
                class="text-2xl font-semibold flex items-center justify-between"
            >
                Tags

                <select
                    id="available_tags"
                    name="available_tags"
                    v-model="selected"
                    @change="addTag()"
                    class="dark:bg-slate-600 mt-1 focus:ring-indigo-500 focus:border-indigo-500 ring-indigo-400 border-indigo-00 block w-60 shadow-sm sm:text-sm rounded-md"
                >
                    <option value="tags" disabled hidden selected>tags</option>
                    <option
                        v-for="tag in availableTags"
                        :key="tag"
                        :value="tag"
                    >
                        {{ tag }}
                    </option>
                </select>

                <input
                    v-model="filterText"
                    type="text"
                    placeholder="new tag"
                    @keypress.enter="addCustomTag()"
                    class="mt-1 dark:bg-slate-600 focus:ring-indigo-500 focus:border-indigo-500 ring-indigo-400 border-indigo-00 block w-60 shadow-sm sm:text-sm rounded-md"
                />
                <button
                    type="button"
                    @click="addCustomTag()"
                    class="flex items-center text-sm py-1 px-4 rounded-sm text-gray-200 bg-gray-600 hover:bg-gray-700"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Add Tag
                </button>
            </h3>
            <div class="flex flex-wrap">
                <span
                    v-for="tag in model.tags"
                    class="text-center text-gray-200 p-5"
                >
                    {{ tag
                    }}<span
                        @click="removeTag(tag)"
                        class="p-2 m-2 bg-slate-500 rounded-sm cursor-pointer"
                        >X</span
                    >
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { savePost, getTags } from "../service";
import UploadPicker from "../components/UploadPicker.vue";
import { useRouter } from "vue-router";
import Draggable from "vuedraggable";
const router = useRouter();

const model = ref({
    title: "",
    description: "",
    image: "",
    tags: [],
});

const image = ref(null);
const showUploads = ref(false);

const tempContent = ref("");
let allTags = ref([]);
let filterText = ref("");
let selected = null;
const active = ref(0);

const contentBlocks = ref([]);

const newContentId = ref(0);

onMounted(async () => {
    const res = await getTags();
    allTags.value = res.data;
});

let availableTags = computed(() => {
    return allTags.value
        .filter((el) => !model.value.tags.includes(el))
        .filter((el) =>
            el.toLowerCase().includes(filterText.value.toLowerCase())
        );
});

function addTag() {
    model.value.tags.push(selected);
}

function addCustomTag() {
    let trimmed = filterText.value.trim();
    if (trimmed) model.value.tags.push(filterText.value);
    filterText.value = "";
}

function removeTag(tag) {
    model.value.tags = model.value.tags.filter((el) => el !== tag);
}

function addContentBlock() {
    if (active.value > 0) {
        const block = contentBlocks.value.find((el) => el.id == active.value);
        block.text = tempContent.value;
        tempContent.value = "";
    }

    newContentId.value = newContentId.value + 1;
    contentBlocks.value.push({ id: newContentId.value, text: "" });
    active.value = newContentId.value;
}

function removeContentBlock(content) {
    contentBlocks.value = contentBlocks.value.filter((el) => el.id !== content);
}

function addUpload(upload) {
    newContentId.value = newContentId.value + 1;
    contentBlocks.value.push({
        id: newContentId.value,
        uploadId: upload.id,
        uploadImage: upload.image ? upload.image.url : "",
        uploadName: upload.filename ? upload.filename : "unnamed",
    });
}

function onImageChoose(ev) {
    model.value.image = ev.target.files[0];
    image.value = URL.createObjectURL(ev.target.files[0]);
}

function setActive(id) {
    if (active.value > 0) {
        const block = contentBlocks.value.find((el) => el.id == active.value);
        block.text = tempContent.value;
        tempContent.value = "";
    }
    if (id > 0) {
        // tempContent.value = contentBlocks.value[id - 1].text;
        tempContent.value = contentBlocks.value.find((el) => el.id == id).text;
    }

    active.value == id ? (active.value = 0) : (active.value = id);
}

function confirm() {
    newPost();
}

async function newPost() {
    const formData = new FormData();
    for (const field in model.value) {
        formData.append(field, model.value[field]);
    }
    if (model.value.image) formData.append("image[]", model.value.image);
    if (model.value.tags) formData.set("tags", model.value.tags.join());
    if (contentBlocks.value.length > 0) {
        const blocks = contentBlocks.value.map(
            (obj) => obj.text || "__upload" + obj.uploadId
        );
        for (var i = 0; i < blocks.length; i++) {
            formData.append("content[]", blocks[i]);
        }
    }
    await savePost({
        formData,
    });
    router.push({ name: "Blog" });
}
</script>
