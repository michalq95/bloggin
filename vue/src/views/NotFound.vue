<template>
    <div>
        <h2>404 - Page not found</h2>
        <p>The requested resource could not be found.</p>
        <span class="cursor-pointer text-purple-500" v-on:click="$router.go(-2)"
            >Return</span
        >
    </div>
</template>

<script>
export default {
    name: "NotFound",
};
</script>
<style lang="scss"></style>
