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
        isMod(state) {
            return state.user.data.role < 2;
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
    },
    modules: {},
});

export default store;
