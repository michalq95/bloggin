import axiosClient from "./axios";
import store from "./store";

export async function getPosts({ page = 1, keyword = "" } = {}) {
    try {
        const res = await axiosClient.get(
            `post?page=${page}&keyword=${keyword}`
        );
        return res.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function getPost(id) {
    try {
        const res = await axiosClient.get(`post/${id}`);
        return res.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function getMoreComments({ model, id, page = 2 } = {}) {
    try {
        const res = await axiosClient.get(
            `${model}/${id}/comment?page=${page}`
        );
        return res.data.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}

export async function saveComment({ model, id, formData } = {}) {
    try {
        const res = await axiosClient.post(`${model}/${id}/comment`, formData);
        return res.data.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
