import { createRouter, createWebHistory } from "vue-router";
import Profile from "@/views/Profile.vue";
import store from "@/store";

import { getMyPermissions } from "@/service";

const routes = [
    {
        path: "/auth",
        name: "Auth",
        redirect: "/login",
        component: () => import("@/components/AuthLayout.vue"),
        meta: { isGuest: true },
        children: [
            {
                path: "/login",
                name: "Login",
                component: () => import("@/views/Login.vue"),
            },
            {
                path: "/register",
                name: "Register",
                component: () => import("@/views/Register.vue"),
            },
        ],
    },
    {
        path: "/",
        name: "Home",
        component: () => import("@/views/Home.vue"),
    },
    {
        path: "/blog",
        name: "Blog",
        component: () => import("@/views/Blog.vue"),
    },
    {
        path: "/blog/:id",
        name: "BlogPost",
        component: () => import("@/views/BlogPost.vue"),
    },
    {
        path: "/401",
        name: "NotAuthorized",
        component: () => import("@/views/NotAuthorized.vue"),
    },
    {
        path: "/tips",
        name: "TipMe",
        component: () => import("@/views/TipMe.vue"),
    },
    {
        path: "/thanks",
        name: "Thanks",
        component: () => import("@/views/Thanks.vue"),
    },
    {
        path: "/image",
        name: "Image",
        component: () => import("@/views/RandomImage.vue"),
    },
    {
        path: "/newblogpost",
        name: "NewBlogpost",
        component: () => import("@/views/NewBlogPost.vue"),
        meta: { requiresAuth: true, requiresPermission: "create post" },
    },
    {
        path: "/profile/:id?",
        name: "Profile",
        component: Profile,
        meta: { requiresAuth: true },
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.name != "NotFound") store.commit("set404Error", false);
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "Login" });
    } else if (store.state.user.token && to.meta.isGuest) {
        next({ name: "Home" });
    } else if (to.meta.requiresPermission) {
        const permissions = await getMyPermissions();
        if (
            !permissions.data.permissions.includes(to.meta.requiresPermission)
        ) {
            next({ name: "NotAuthorized" });
        }
        next();
    } else {
        next();
    }
});
export default router;
