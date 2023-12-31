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
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { getUploads } from "../service";

const emit = defineEmits(["selectUpload"]);
const uploads = ref([]);

function pickUpload(upload) {
    emit("selectUpload", upload);
}

onMounted(async () => {
    const data = await getUploads();
    uploads.value = data.data;
});
</script>
