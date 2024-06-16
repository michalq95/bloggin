<template>
    <div>
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-filter backdrop-blur-sm flex justify-center items-center"
            @click="closeModal"
        ></div>

        <div
            class="fixed top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-md p-4 shadow-lg"
        >
            <div @click.stop>
                <h2>Want to rate posts?</h2>
                <div
                    class="cursor-pointer text-xl text-slate-700 hover:text-xxl"
                    @click="goToRegister"
                >
                    Register!
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const closeModal = () => {
    store.commit("setModal", null);
};

function goToRegister() {
    closeModal();
    router.push({ name: "Register" });
}
</script>
