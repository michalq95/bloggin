import { mount } from "@vue/test-utils";
import Blog from "@/views/Blog.vue";
import { getPosts } from "@/service";
import { createStore } from "vuex";
import store from "@/store";

const mockStore = createStore({
    state: {
        user: {
            data: {
                a: true,
            },
        },
    },
    getters: { isLoggedIn: jest.fn(), getPermissions: jest.fn(() => []) },
});

jest.mock("@/service", () => ({
    getPosts: jest.fn(),
}));

describe("Blog", () => {
    it("renders the list", async () => {
        const mockData = {
            data: {
                data: [],
                meta: { current_page: 1 },
            },
        };
        getPosts.mockResolvedValue(mockData);

        const wrapper = mount(Blog, {
            global: {
                plugins: [mockStore],
                stubs: ["BlogPostComponent", "router-link", "v-pagination"],
            },
        });

        expect(getPosts).toHaveBeenCalled();
        await flushPromises();
        await wrapper.vm.$nextTick();
    });
});
