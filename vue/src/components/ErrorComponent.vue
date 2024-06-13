<template>
    <div
        v-if="$store.state.error"
        class="py-3 px-4 bg-red-400 text-black rounded flex items-center justify-between top-1/3 fixed left-0 mx-auto w-72"
    >
        {{ $store.state.error }}
        <span
            class="cursor-pointer text-right rounded-full"
            @click="$store.commit('setError', '')"
            >X</span
        >
    </div>
</template>
<script setup>
import store from "@/store";
</script>
