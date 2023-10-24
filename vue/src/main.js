import { createApp } from "vue";
import "./style.css";
import "./index.css";
import store from "./store";
import router from "./router";

import App from "./App.vue";

import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
const vuetify = createVuetify({
    components,
    directives,
});

import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

createApp(App)
    .use(store)
    .use(router)
    .use(vuetify)
    .component("QuillEditor", QuillEditor)
    .mount("#app");
