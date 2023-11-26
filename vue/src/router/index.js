import { createRouter, createWebHistory } from "vue-router";
import AuthLayout from "../components/AuthLayout.vue";
import Login from "../views/Login.vue";
import Blog from "../views/Blog.vue";
import BlogPost from "../views/BlogPost.vue";
import Register from "../views/Register.vue";
import NewBlogPost from "../views/NewBlogPost.vue";
import NotAuthorized from "../views/NotAuthorized.vue";
import TipMe from "../views/TipMe.vue";
import Thanks from "../views/Thanks.vue";
import Home from "../views/Home.vue";
import store from "../store";

const routes = [
    {
        path: "/auth",
        name: "Auth",
        redirect: "/login",
        component: AuthLayout,
        meta: { isGuest: true },
        children: [
            { path: "/login", name: "Login", component: Login },
            { path: "/register", name: "Register", component: Register },
        ],
    },
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/blog",
        name: "Blog",
        component: Blog,
    },
    {
        path: "/blog/:id",
        name: "BlogPost",
        component: BlogPost,
    },
    {
        path: "/401",
        name: "NotAuthorized",
        component: NotAuthorized,
    },
    {
        path: "/tips",
        name: "TipMe",
        component: TipMe,
    },
    {
        path: "/thanks",
        name: "Thanks",
        component: Thanks,
    },
    {
        path: "/newblogpost",
        name: "NewBlogpost",
        component: NewBlogPost,
        meta: { requiresAuth: true, requiresPermission: "create post" },
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    if (to.name != "NotFound") store.commit("set404Error", false);
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "Login" });
    } else if (
        to.meta.requiresPermission &&
        !store.getters.getPermissions.includes(to.meta.requiresPermission)
    ) {
        next({ name: "NotAuthorized" });
    } else if (store.state.user.token && to.meta.isGuest) {
        next({ name: "Home" });
    } else {
        next();
    }
});
export default router;
