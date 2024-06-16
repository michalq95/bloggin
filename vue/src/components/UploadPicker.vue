<template>
    <div class="my-4 flex flex-wrap">
        <div v-for="upload in uploads">
            <div class="bg-slate-500 items-center m-1 w-36 truncate">
                {{ upload.filename ? upload.filename : "unnamed" }}
                <img
                    v-if="upload.image"
                    class="mx-2 w-32 h-32 object-cover rounded-sm"
                    :src="upload.image.url"
                    alt="avatar"
                />
                <img
                    v-else
                    class="mx-2 w-32 h-32 object-cover rounded-sm"
                    :src="getMiniature(upload.mimetype)"
                    alt="placeholder_avatar"
                />
                <button
                    class="relative p-2 m-2 bg-slate-600 rounded-sm"
                    @click="pickUpload(upload)"
                >
                    Add
                </button>
                <button
                    class="relative p-2 m-2 bg-slate-600 rounded-sm"
                    @click="removeUpload(upload)"
                >
                    Remove
                </button>
            </div>
        </div>
    </div>
    <div class="my-2">
        <label
            @click.stop
            class="cursor-pointer flex justify-start shrink-0"
            for="file-input-2"
        >
            <span
                class="flex items-center justify-center h-32 w-32 min-w-12 rounded-md bg-gray-100"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-32 w-32 text-gray-500"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                        clip-rule="evenodd"
                    />
                </svg>
            </span>
            <button class="flex p-4 font-semibold">Add new asset</button>
        </label>
        <input
            id="file-input-2"
            type="file"
            accept="image/*, video/*, audio/*,.txt,.doc,.docx"
            @change="onAssetChoose"
            style="display: none"
        />
        <div class="flex" v-if="newUpload">
            <img
                v-if="tempMiniature"
                :src="tempMiniature"
                class="h-32 w-32 min-w-12"
            />
            <div class="truncate">
                {{ newUploadName }}
                <div
                    @click="uploadFile"
                    class="p-2 m-2 bg-slate-500 rounded-sm cursor-pointer"
                >
                    Upload File
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { getUploads, postUpload, deleteUpload } from "@/service";

const emit = defineEmits(["selectUpload"]);
const uploads = ref([]);
const newUpload = ref(null);
const newUploadName = ref("");
const tempMiniature = ref(null);

function pickUpload(upload) {
    emit("selectUpload", upload);
}
async function removeUpload(upload) {
    await deleteUpload(upload.id);
    await loadUploads();
}
const fileTypeToMiniature = computed(() => ({
    // image: URL.createObjectURL,
    audio: "./src/assets/notes.png",
    video: "./src/assets/video.png",
}));
function getMiniature(mimetype) {
    const fileType = mimetype?.split("/")[0];

    return (
        fileTypeToMiniature.value[fileType] || "./src/assets/placeholder.jpg"
    );
}
function onAssetChoose(ev) {
    newUploadName.value = ev.target.files[0].name;
    newUpload.value = ev.target.files[0];
    if (ev.target?.files[0]?.type.startsWith("image")) {
        tempMiniature.value = URL.createObjectURL(ev.target.files[0]);
    } else if (ev.target?.files[0]?.type.startsWith("audio")) {
        tempMiniature.value = "./src/assets/notes.png";
    } else if (ev.target?.files[0]?.type.startsWith("video")) {
        tempMiniature.value = "./src/assets/video.png";
    } else {
        tempMiniature.value = "./src/assets/placeholder.jpg";
    }
}

async function loadUploads() {
    const data = await getUploads();
    uploads.value = data.data;
}

async function uploadFile() {
    const formData = new FormData();
    formData.append("file[]", newUpload.value);
    const newAsset = await postUpload({
        formData,
    });
    if (newAsset?.data.length > 0) {
        loadUploads();
    }
}

onMounted(async () => {
    await loadUploads();
});
</script>
