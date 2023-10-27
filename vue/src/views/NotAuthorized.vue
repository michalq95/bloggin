<template>
    <div>
        <h2>401 - Not Authorized</h2>
        <p>You are not authorized to enter this page</p>
        <span class="cursor-pointer text-purple-500" v-on:click="$router.go(-2)"
            >Return</span
        >
    </div>
</template>

<script>
export default {
    name: "NotAuthorized",
};
</script>
<style lang="scss"></style>
