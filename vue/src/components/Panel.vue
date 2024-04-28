<template>
    <div
        class="flex justify-between items-center py-3 px-5 shadow-md bg-sky-300 hover:bg-sky-200 dark:bg-sky-950 dark:hover:bg-sky-800 h-[80px]"
    >
        <div>
            <img
                v-if="item.image"
                :src="item.image.url"
                class="w-8 object-cover"
            />
            <img
                v-else
                src="../assets/placeholder.jpg"
                class="w-8 object-cover"
            />
        </div>
        <div class="flex flex-col flex-grow p-2">
            <div class="flex justify-between">
                <h4>{{ item.title }}</h4>
                <!-- <h4>{{ item.user.name }}</h4> -->
                <router-link
                    :to="{ name: 'Profile', params: { id: item.user.id } }"
                >
                    <Avatar
                        :image="item.user.image?.url"
                        :name="item.user.name"
                    ></Avatar>
                </router-link>
            </div>

            <div class="flex justify-between">
                <h5 class="items-end" v-html="item.description"></h5>

                <div>
                    <span v-for="tag in item.tags.slice(0, 3)" class="pl-2">
                        {{ tag }}
                    </span>
                </div>
            </div>
            <!-- {{ item }} -->
            <div class="flex justify-between">
                <router-link
                    :to="{
                        name: 'BlogPost',
                        params: { id: item.ancestor },
                    }"
                    >Go to Post</router-link
                >

                <div>
                    {{ item.created_at }}
                </div>
            </div>
        </div>
        <!-- <div
            v-if="offer.company_id === userCompanyId"
            class="flex flex-col justify-between items-center"
        >
            <router-link
                :to="{ name: 'OfferCreate', params: { id: offer.slug } }"
                class="py-2 px-2 bg-sky-300 hover:bg-sky-200 dark:bg-sky-900 dark:hover:bg-sky-700 rounded-lg"
                >Edit offer</router-link
            >
            <button
                v-if="offer.id"
                type="button"
                @click="deleteOffer(offer)"
                class="h-8 w-8 items-center text-red-700 bg-red-300"
            >
                X
            </button>
        </div> -->
    </div>
</template>

<script setup>
import store from "../store";
import Avatar from "./Avatar.vue";
import { computed, ref } from "vue";

const props = defineProps({
    item: Object,
});

// const item = ref(initialProp);
</script>

<style lang="scss" scoped></style>
