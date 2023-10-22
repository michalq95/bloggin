<template>
    <li
        class="bg-sky-300 hover:bg-sky-200 dark:bg-sky-900 dark:hover:bg-sky-800 rounded-sm"
    >
        <div class="">
            {{ comment.id }}

            {{ comment.title }}
            {{ comment.description }}

            <ul>
                <Comment
                    v-for="nestedComment in comment.comments"
                    :comment="nestedComment"
                >
                </Comment>
            </ul>
        </div>
    </li>
</template>

<script setup>
const props = defineProps({
    comment: Object,
});
</script>
