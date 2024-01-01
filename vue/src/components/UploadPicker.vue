<template>
    <div class="my-4 flex flex-wrap">
        <div v-for="upload in uploads">
            <div class="bg-slate-500 items-center m-1">
                <!-- {{ upload }} -->
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
                    src="../assets/placeholder.jpg"
                    alt="placeholder_avatar"
                />
                <button
                    class="relative p-2 m-2 bg-slate-600 rounded-sm"
                    @click="pickUpload(upload)"
                >
                    Add
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
            <button class="flex p-4 font-semibold">Add new asset</button>
        </label>
        <input
            id="file-input-2"
            type="file"
            accept="image/*, video/*, audio/*,.txt,.doc,.docx"
            @change="onAssetChoose"
            style="display: none"
        />
        <div v-if="newUpload">
            {{ newUploadName }}
            <div
                @click="uploadFile"
                class="p-2 m-2 bg-slate-500 rounded-sm cursor-pointer"
            >
                Upload File
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { getUploads, postUpload } from "../service";

const emit = defineEmits(["selectUpload"]);
const uploads = ref([]);
const newUpload = ref(null);
const newUploadName = ref("");

function pickUpload(upload) {
    emit("selectUpload", upload);
}

function onAssetChoose(ev) {
    newUploadName.value = ev.target.files[0].name;
    newUpload.value = ev.target.files[0];
}

async function uploadFile() {
    //dawac to bezposrediio i crudowac i wogle
    const formData = new FormData();
    formData.append("file[]", newUpload.value);
    await postUpload({
        formData,
    });
}

onMounted(async () => {
    const data = await getUploads();
    uploads.value = data.data;
});
</script>
