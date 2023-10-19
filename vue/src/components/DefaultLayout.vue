<template>
    <div class="h-full w-full">
        <header
            class="md:mb-8 py-4 px-4 bg-slate-400 flex justify-between items-center select-none"
        >
            <div
                class="text-2xl font-semibold flex-none justify-between items-center"
            >
                <router-link :to="{ name: 'Home' }"> Home </router-link>
            </div>
            <div class="flex grow"></div>
            <div
                v-if="token"
                class="relative inline-block text-left items-end"
            ></div>

            <div class="flex-none pl-2">
                <div class="text-gray-800 sm:block md:hidden">
                    <div @click="menuIsOpen = !menuIsOpen" v-show="!menuIsOpen">
                        <svg
                            class="fill-current w-8 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                            />
                        </svg>
                    </div>

                    <div @click="menuIsOpen = !menuIsOpen" v-show="menuIsOpen">
                        <svg
                            class="fill-current w-8 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:block text-sm">
                    <div v-if="!token">
                        <router-link
                            :to="{ name: 'Login' }"
                            class="py-2 px-3 ml-2 hover:bg-indigo-100 rounded"
                        >
                            Log In
                        </router-link>

                        <router-link
                            :to="{ name: 'Register' }"
                            class="py-2 px-3 ml-2 hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg border text-white"
                        >
                            Register
                        </router-link>
                    </div>

                    <div v-else>
                        <span class="py-2 px-3 ml-2 rounded">
                            <img
                                :src="user.image"
                                alt=""
                                class="inline w-8 object-cover"
                            />
                            Welcome {{ user.name }}!
                        </span>

                        <span
                            @click="logout"
                            class="py-2 px-3 ml-2 hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg border text-white"
                        >
                            Logout
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="transform opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="transform opacity-0"
        >
            <div
                class="bg-white px-4 py-4 select-none border-b md:hidden duration-200"
                v-if="menuIsOpen"
            >
                <div v-if="!token">
                    <router-link
                        :to="{ name: 'Login' }"
                        class="block mb-2 font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Log In
                    </router-link>

                    <router-link
                        :to="{ name: 'Register' }"
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Register
                    </router-link>
                </div>
                <div v-else>
                    <div
                        class="block font-semibold text-gray-800 py-2 px-3 rounded"
                    >
                        <img
                            :src="user.image"
                            alt=""
                            class="inline w-8 object-cover"
                        />

                        Welcome {{ user.name }}!
                    </div>

                    <div
                        @click="logout"
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Logout
                    </div>
                </div>
            </div>
        </Transition>

        <div v-if="$store.state.error404">404 not found</div>
        <router-view :key="$route.fullPath" v-else></router-view>
    </div>
</template>

<script setup>
import { useStore } from "vuex";

import { computed, reactive, ref } from "vue";
import { useRouter } from "vue-router";

const menuIsOpen = ref(false);

const store = useStore();
const router = useRouter();
let isMod = computed(() => store.getters.isMod);
const user = computed(() => store.state.user.data);
const token = computed(() => store.state.user.token);

function logout() {
    store.dispatch("logout").then(() => {
        router.push({
            name: "Home",
        });
    });
}
</script>

<style scoped lang="scss"></style>
