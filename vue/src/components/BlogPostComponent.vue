<template>
    <div class="px-10 py-6 bg-white rounded-lg shadow-md flex content-center">
        <ScoreComponent
            :score="post.score"
            :id="post.id"
            :type="'post'"
            :myVote="post.vote?.vote"
            @voteOnElement="elementVoted"
        />
        <div class="grow">
            <router-link
                class="text-2xl text-gray-700 font-bold hover:underline"
                :to="{ name: 'BlogPost', params: { id: post.id } }"
                >{{ post.title }}</router-link
            >
            <div class="flex justify-between items-center">
                <ImageComponent
                    v-if="post.image"
                    :imageUrl="post.image.url"
                    :width="96"
                ></ImageComponent>
                <span>
                    <div class="font-light text-gray-600">
                        {{ post.created_at }}
                    </div>
                    <div
                        v-if="post.tags.length > 0"
                        class="px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded"
                    >
                        <button
                            v-for="tag in post.tags"
                            :key="tag"
                            class="hover:bg-gray-500 mx-1 px-1"
                            @click="addTag(tag)"
                        >
                            {{ tag }}
                        </button>
                    </div>
                </span>
            </div>

            <div class="mt-2 flex">
                <p class="text-gray-600" v-html="post.description"></p>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div>
                    <router-link
                        class="text-blue-500 hover:underline"
                        :to="{
                            name: 'BlogPost',
                            params: { id: post.id },
                        }"
                        >Read more &rarr;
                    </router-link>
                    <div>{{ post.comments_count }} replies</div>
                </div>
                <router-link
                    :to="{
                        name: 'Profile',
                        params: { id: post.user.id },
                    }"
                >
                    <Avatar
                        :image="post.user.image?.url"
                        :name="post.user.name"
                    ></Avatar>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import ImageComponent from "@/components/ImageComponent.vue";
import ScoreComponent from "@/components/ScoreComponent.vue";
import Avatar from "@/components/Avatar.vue";

const emit = defineEmits(["elementVoted", "addTag"]);
const props = defineProps({
    post: Object,
});

function elementVoted(data) {
    emit("elementVoted", data);
}

function addTag(tag) {
    emit("addTag", tag);
}
</script>
