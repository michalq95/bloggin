<template>
    <div @click="displayModal">
        <div
            :class="{
                'text-red-500':
                    optimisticVote === 1 ||
                    (optimisticVote == null && myVote === 1),
            }"
            class="cursor-pointer p-2 bg-slate-400 rounded-full"
            @click="rate(1)"
        >
            ^
        </div>
        <div>{{ optimisticScore ?? score }}</div>
        <div
            :class="{
                'text-red-500':
                    optimisticVote === -1 ||
                    (optimisticVote == null && myVote === -1),
            }"
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
import { useStore } from "vuex";
const store = useStore();

const isLoggedIn = computed(() => store.getters.isLoggedIn);
const emit = defineEmits(["voteOnElement"]);
const props = defineProps({
    score: Number,
    id: Number,
    type: String,
    myVote: Number,
});
const optimisticVote = ref(null);
const optimisticScore = ref(null);
function displayModal() {
    console.log(isLoggedIn.value);
    if (isLoggedIn.value) return;
    store.commit("setModal", "goRegister");
}
async function rate(value) {
    if (!isLoggedIn.value) return;

    const newVote = value == props.myVote ? 0 : value;
    optimisticVote.value = newVote;
    optimisticScore.value = props.myVote
        ? props.score - props.myVote + newVote
        : props.score + newVote;
    const data = await vote({ model: props.type, id: props.id, vote: newVote });

    emit("voteOnElement", { ...data.data, value: newVote });
    optimisticVote.value = null;
    optimisticScore.value = null;
}
</script>
