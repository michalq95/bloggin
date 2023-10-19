import axios from "axios";
import store from "./store";
// import router from "./router";

const axiosClient = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
});

axiosClient.interceptors.request.use((config) => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`;
    return config;
});

axiosClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 404) {
            store.commit("set404Error", true);
            // router.replace({ name: "NotFound" });
            // history.replaceState({}, "Not Found", error.response.request.responseURL);
        }
        return Promise.reject(error);
    }
);

export default axiosClient;
