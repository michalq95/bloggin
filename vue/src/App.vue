<script setup>
import DefaultLayout from "./components/DefaultLayout.vue";
import ErrorComponent from "./components/ErrorComponent.vue";
import store from "./store";
</script>

<template>
    <!-- <router-view></router-view> -->
    <DefaultLayout></DefaultLayout>
    <ErrorComponent></ErrorComponent>
</template>

<style scoped>
.logo {
    height: 6em;
    padding: 1.5em;
    will-change: filter;
    transition: filter 300ms;
}
.logo:hover {
    filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
    filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
