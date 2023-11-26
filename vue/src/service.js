import axiosClient from "./axios";
import store from "./store";

export async function login(user) {
    try {
        return await axiosClient.post("/login", user);
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function register(user) {
    try {
        return await axiosClient.post("/register", user);
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function logout() {
    console.log("log out function");
    try {
        return await axiosClient.post("/logout");
    } catch (error) {
        store.commit("setError", error);
        return null;
    }
}

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
export async function getDonations() {
    try {
        const res = await axiosClient.get(`donation`);
        return res.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function initiateDonation(id) {
    try {
        const res = await axiosClient.post(`donation/${id}`);
        // console.log(res);
        return res;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function donationSuccess(token) {
    try {
        const res = await axiosClient.post(`donation/success`, { token });
        return res;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function donationFailure(token, error) {
    try {
        const res = await axiosClient.post(`donation/failure`, {
            token,
            error,
        });
        return res;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}

export async function getTags() {
    try {
        const res = await axiosClient.get(`/tag`);
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
export async function savePost({ formData } = {}) {
    try {
        const res = await axiosClient.post(`post`, formData);
        return res.data.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function putComment({ id, formData }) {
    try {
        const res = await axiosClient.post(`comment/${id}`, formData);
        return res.data.data;
    } catch (error) {
        store.commit("setError", error.response.data.message);
        return null;
    }
}
export async function downloadUploadedFile(id) {
    try {
        const res = await axiosClient.get(`upload/${id}`, {
            responseType: "blob",
        });

        const url = window.URL.createObjectURL(new Blob([res.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute(
            "download",
            `file-${id}.${res.data.type.match(/\/([^\/]+)$/)[1]}`
        );
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.log(error);
        store.commit("setError", error.response.data.message);
        return null;
    }
}
