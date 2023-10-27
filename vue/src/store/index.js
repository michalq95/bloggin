import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem("BLOG_USER")) || {},
            token: sessionStorage.getItem("BLOG_TOKEN"),
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
            sessionStorage.removeItem("BLOG_TOKEN");
            sessionStorage.removeItem("BLOG_USER");
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem("BLOG_USER", JSON.stringify(userData.user));

            sessionStorage.setItem("BLOG_TOKEN", userData.token);
        },
    },
    modules: {},
});

export default store;
