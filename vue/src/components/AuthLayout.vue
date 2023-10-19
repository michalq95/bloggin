<template>
    <div
        class="flex min-h-full bg-gray-400 flex-1 flex-col justify-center px-6 py-12 lg:px-8"
    >
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    name: "AuthLayout",
};
</script>
<style scoped lang="scss"></style>
