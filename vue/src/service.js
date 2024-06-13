import axiosClient from "./axios";
import store from "./store";

const [GET, POST] = ["get", "post"];

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
    return makeRequest(POST, "/login", user);
}

export async function register(user) {
    return makeRequest(POST, "/register", user);
}

export async function logout() {
    return makeRequest(POST, "/logout");
}

export async function getPosts({ page = 1, keyword = "" } = {}) {
    const url = `post?page=${page}&keyword=${keyword}`;
    return makeRequest(GET, url);
}

export async function getDonations() {
    return makeRequest(GET, "donation");
}
export async function initiateDonation(id) {
    return makeRequest(POST, `donation/${id}`);
}

export async function donationSuccess(token) {
    return makeRequest(POST, "donation/success", { token });
}

export async function donationFailure(token, error) {
    return makeRequest(POST, "donation/failure", { token, error });
}

export async function getTags() {
    return makeRequest(GET, "/tag");
}
export async function getUploads() {
    return makeRequest(GET, "/upload");
}
export async function getMyPermissions() {
    return makeRequest(GET, "/permissions");
}

export async function getPost(id) {
    return makeRequest(GET, `post/${id}`);
}

export async function getMoreComments({ model, id, page = 2 } = {}) {
    const url = `${model}/${id}/comment?page=${page}`;
    return makeRequest(GET, url);
}

export async function saveComment({ model, id, formData } = {}) {
    const url = `${model}/${id}/comment`;
    return makeRequest(POST, url, formData);
}

export async function savePost({ formData } = {}) {
    return makeRequest(POST, "post", formData);
}

export async function putComment({ id, formData }) {
    return makeRequest(POST, `comment/${id}`, formData);
}
export async function postUpload({ formData }) {
    return makeRequest(POST, `upload`, formData);
}
export async function deleteUpload(id) {
    return makeRequest("delete", `upload/${id}`);
}

export async function saveUserImage({ formData } = {}) {
    return makeRequest(POST, "image", formData);
}
export async function getRandomImage() {
    return makeRequest(GET, "random-image");
}
export async function vote({ model, id, vote } = {}) {
    const url = `${model}/${id}/vote`;
    return makeRequest(POST, url, { vote });
    // return new Promise((resolve) => {
    //     setTimeout(() => {
    //         resolve();
    //     }, 1000);
    // });
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
