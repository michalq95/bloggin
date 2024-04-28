<template>
    <div
        class="justify-between text-left py-3 px-5 my-2 shadow-md dark:bg-slate-600"
    >
        <h2>{{ user.name }}</h2>
        <ProfileSettings
            v-if="user.id == route.params.id || !route.params.id"
        ></ProfileSettings>
        Comments:
        <div v-for="comment in comments.data">
            <Panel :item="comment" />
        </div>
    </div>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import Panel from "../components/Panel.vue";
import ProfileSettings from "../components/ProfileSettings.vue";
import { getMoreComments } from "../service";

const route = useRoute();
const router = useRouter();

const store = useStore();
const user = computed(() => store.state.user.data);

const posts = ref([]);
const comments = ref({
    to: null,
    data: [],
});

console.log(user);
console.log(user.value);
console.log(user.value.id);
onMounted(async () => {
    const id = route.params.id || user.value.id;

    if (!id) {
        router.push({ name: "Home" });
    }
    const data = await getMoreComments({
        model: "user",
        id: id,
        page: 1,
    });
    comments.value = { ...data };
    //only self so far, will add to add universal
});
</script>
