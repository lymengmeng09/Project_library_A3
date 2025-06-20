<template>
    <nav class="h-16 flex w-10/12 m-auto items-center justify-between ">
        <div id="nav-item" class="flex items-center gap-12">
            <NavigationItem v-for="item in navItems" :key="item.label" :item="item" :to="item.path" />
        </div>
    </nav>
</template>

<script setup>
import NavigationItem from "./NavigationItem.vue";
import { ref } from "vue";

const navItems = ref([
    { label: "Book", path: "/", icon: "auto_stories"},
    { label: "Member", path: "/member", icon: "groups" },
    { label: "Author", path: "/author", icon: "face" },

]);
</script>
