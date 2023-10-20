import axiosClient from "./axios";
import store from "./store";

export async function getPosts() {
    try {
        const res = await axiosClient.get(`post`);
        return res.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
