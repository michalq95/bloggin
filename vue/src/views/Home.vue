<template>
    <div class="container mx-auto text-gray-900 dark:text-white">
        <div class="px-4 md:block">
            <div class="pb-8 mt-auto">
                <div class="text-center">
                    <h1
                        class="text-2xl md:text-3xl mb-8 mt-16 font-serif max-w-3xl mx-auto"
                    >
                        BlogPage
                    </h1>

                    <div
                        class="md:max-w-2xl mx-auto text-gray-800 dark:text-white"
                    >
                        <p>
                            Description for few sentences. Description for few
                            sentences. Description for few sentences.
                            Description for few sentences.
                        </p>
                    </div>

                    <div class="mb-6 mt-12">
                        <a
                            href="#"
                            class="py-2 px-4 md:py-4 md:px-6 mr-2 border select-none hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg text-white"
                            >Why this app?</a
                        >

                        <router-link
                            id="get-started"
                            :to="user ? { name: 'Blog' } : { name: 'Register' }"
                            class="py-2 px-4 md:py-4 md:px-6 border select-none hover:bg-gray-100 bg-white rounded shadow-lg text-indigo-800"
                        >
                            Get Started <span>&rarr;</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useStore } from "vuex";
import { computed } from "vue";

const store = useStore();
const user = computed(() => store.state.user.data);
</script>
