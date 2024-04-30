<template>
    <div>
        {{ myVote }}
        <div
            :class="{ 'text-red-500': myVote === 1 }"
            class="cursor-pointer p-2 bg-slate-400 rounded-full"
            @click="rate(1)"
        >
            ^
        </div>
        <div>{{ score }}</div>
        <div
            :class="{ 'text-red-500': myVote === -1 }"
            class="cursor-pointer p-2 bg-slate-400 rounded-full"
            @click="rate(-1)"
        >
            V
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { vote } from "../service";
let isLoggedIn = computed(() => store.getters.isLoggedIn);
const emit = defineEmits(["voteOnElement"]);

const props = defineProps({
    score: Number,
    id: Number,
    type: String,
    myVote: Number,
});

async function rate(value) {
    if (!isLoggedIn) return;
    const newVote = value == props.myVote ? 0 : value;
    const data = await vote({ model: props.type, id: props.id, vote: newVote });
    emit("voteOnElement", { ...data.data, value });
}
</script>
