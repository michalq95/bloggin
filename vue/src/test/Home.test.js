import { mount } from "@vue/test-utils";
import Home from "@/views/Home.vue";
import { createStore } from "vuex";

const mockRoute = {
    params: {
        id: 1,
    },
};
const mockRouter = {
    push: jest.fn(),
};
const mockStore = createStore({
    state: {
        user: {
            data: {
                a: true,
            },
        },
    },
});

test("renders a homePage", () => {
    const wrapper = mount(Home, {
        global: {
            plugins: [mockStore],
            mocks: {
                $route: mockRoute,
                $router: mockRouter,
            },
        },
    });

    const homeTitle = wrapper.get("h1");

    expect(homeTitle.text()).toBe("BlogPage");
});
