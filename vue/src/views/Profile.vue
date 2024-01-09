<template>
    <div>{{ user }}</div>
    <div>{{ model }}</div>
    <div
        class="justify-between text-left py-3 px-5 my-2 shadow-md dark:bg-slate-600"
    >
        <h2>{{ user.name }}</h2>
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

        <!-- <v-switch label="Dark mode" v-model="model.dark" inset></v-switch> -->
        <button @click="toggleDark()">Toggle Color Mode</button>
        <button
            class="flex p-2 m-2 bg-slate-700 rounded-sm font-extrabold"
            @click="confirm()"
        >
            Save settings
        </button>
    </div>
</template>
<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useDark, useToggle } from "@vueuse/core";
const isDark = useDark();
const toggleDark = useToggle(isDark);

const image = ref();

const store = useStore();
const user = computed(() => store.state.user.data);
const model = ref({
    image: "",
    dark: user.darkMode ?? false,
});
function onImageChoose(ev) {
    model.value.image = ev.target.files[0];
    image.value = URL.createObjectURL(ev.target.files[0]);
}
function confirm() {
    const formData = new FormData();
}
</script>
