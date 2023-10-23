<template>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <img
            class="mx-auto h-10 w-auto"
            src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
            alt="Your Company"
        />
        <h2
            class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
        >
            Register
        </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit="register">
            <div>
                <label
                    for="fullname"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Name</label
                >
                <div class="mt-2">
                    <input
                        id="fullname"
                        name="name"
                        type="name"
                        autocomplete="name"
                        required=""
                        placeholder="name"
                        v-model="user.name"
                        class="block w-full rounded-md bg-zinc-200 border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>
            <div>
                <label
                    for="email"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Email address</label
                >
                <div class="mt-2">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required=""
                        v-model="user.email"
                        placeholder="email"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>

            <div>
                <label
                    for="password"
                    class="block font-medium leading-6 text-gray-900"
                    >Password</label
                >
                <div class="mt-2">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required=""
                        placeholder="password"
                        v-model="user.password"
                        class="block w-full rounded-t-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
                <div>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="password confirmation"
                        autocomplete="current-password_confirmation"
                        required=""
                        v-model="user.password_confirmation"
                        class="block w-full rounded-b-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Sign in
                </button>
            </div>
        </form>

        <p class="mt-10 text-center text-sm text-gray-500">
            <router-link
                :to="{ name: 'Login' }"
                class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
                >Has an account?</router-link
            >
        </p>
    </div>
</template>

<script setup>
import store from "../store";
import { useRouter } from "vue-router";
import { register as registerService } from "../service";

const router = useRouter();
const user = {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
};

async function register() {
    const data = await registerService(user);
    if (data) {
        store.commit("setUser", data.data);
        router.push({ name: "Home" });
    }
}
</script>
<style scoped lang="scss"></style>
