import axiosClient from "./axios";
import store from "./store";

async function makeRequest(method, url, data = null) {
    try {
        const config = { method, url, data };
        const response = await axiosClient(config);

        return response.data;
    } catch (error) {
        const errorMessage =
            error.response?.data?.message || "An error occurred";
        store.commit("setError", errorMessage);
        return null;
    }
}

export async function login(user) {
    return makeRequest("post", "/login", user);
}

export async function register(user) {
    return makeRequest("post", "/register", user);
}

export async function logout() {
    return makeRequest("post", "/logout");
}

export async function getPosts({ page = 1, keyword = "" } = {}) {
    const url = `post?page=${page}&keyword=${keyword}`;
    return makeRequest("get", url);
}

export async function getDonations() {
    return makeRequest("get", "donation");
}
export async function initiateDonation(id) {
    return makeRequest("post", `donation/${id}`);
}

export async function donationSuccess(token) {
    return makeRequest("post", "donation/success", { token });
}

export async function donationFailure(token, error) {
    return makeRequest("post", "donation/failure", { token, error });
}

export async function getTags() {
    return makeRequest("get", "/tag");
}
export async function getUploads() {
    return makeRequest("get", "/upload");
}
export async function getMyPermissions() {
    return makeRequest("get", "/permissions");
}

export async function getPost(id) {
    return makeRequest("get", `post/${id}`);
}

export async function getMoreComments({ model, id, page = 2 } = {}) {
    const url = `${model}/${id}/comment?page=${page}`;
    return makeRequest("get", url);
}

export async function saveComment({ model, id, formData } = {}) {
    const url = `${model}/${id}/comment`;
    return makeRequest("post", url, formData);
}

export async function savePost({ formData } = {}) {
    return makeRequest("post", "post", formData);
}

export async function putComment({ id, formData }) {
    return makeRequest("post", `comment/${id}`, formData);
}
export async function postUpload({ formData }) {
    return makeRequest("post", `upload`, formData);
}
export async function deleteUpload(id) {
    return makeRequest("delete", `upload/${id}`);
}

export async function saveUserImage({ formData } = {}) {
    return makeRequest("post", "image", formData);
}
export async function vote({ model, id, vote } = {}) {
    const url = `${model}/${id}/vote`;
    return makeRequest("post", url, { vote });
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
        store.commit("setError", error.response.data.message);
        return null;
    }
}
