import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
    state: {
        user: {
            data: JSON.parse(localStorage.getItem("BLOG_USER")) || {},
            token: localStorage.getItem("BLOG_TOKEN"),
        },
        error: "",
    },
    getters: {
        isLoggedIn(state) {
            return state.user.token;
        },
        getPermissions(state) {
            return state.user.data.permissions ?? [];
        },
    },
    actions: {},
    mutations: {
        setError: (state, value) => {
            state.error = value;
        },
        set404Error: (state, value) => {
            state.error404 = value;
        },
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            localStorage.removeItem("BLOG_TOKEN");
            localStorage.removeItem("BLOG_USER");
        },
        setUser: (state, { userData, token = null }) => {
            console.log(userData, token);
            state.user.data = userData;
            localStorage.setItem("BLOG_USER", JSON.stringify(userData));
            if (token) {
                state.user.token = token;
                localStorage.setItem("BLOG_TOKEN", token);
            }
        },
    },
    modules: {},
});

export default store;
