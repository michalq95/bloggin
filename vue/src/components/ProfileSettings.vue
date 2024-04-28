<template>
    <h2>{{ user.email }}</h2>
    <div class="my-2">
        <label
            @click.stop
            class="cursor-pointer flex shrink-0"
            for="file-input"
        >
            <img
                v-if="image"
                :src="image"
                class="rounded-md h-32 w-32 min-w-12 object-cover"
            />
            <img
                v-else-if="user.image"
                :src="user.image.url"
                class="rounded-md h-32 w-32 min-w-12 object-cover"
            />
            <span
                v-else
                class="items-center justify-center h-32 w-32 min-w-12 rounded-md bg-gray-100"
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
            <button class="flex p-4 font-semibold">Select avatar</button>
        </label>
        <input
            id="file-input"
            type="file"
            accept="image/*"
            @change="onImageChoose"
            style="display: none"
        />
    </div>

    <button
        class="flex p-2 m-2 bg-slate-700 rounded-sm font-extrabold"
        @click="confirm()"
    >
        Save settings
    </button>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { saveUserImage } from "../service";

const store = useStore();
const user = computed(() => store.state.user.data);

const image = ref();
const model = ref({
    image: "",
});
function onImageChoose(ev) {
    model.value.image = ev.target.files[0];
    image.value = URL.createObjectURL(ev.target.files[0]);
}
async function confirm() {
    if (model.value.image) {
        const formData = new FormData();
        formData.append("image[]", model.value.image);

        const user = await saveUserImage({ formData });
        console.log(user.data);
        if (user) {
            store.commit("setUser", { userData: user.data });
        }
    } else {
        store.commit("setError", "Select image to upload");
    }
}
</script>
