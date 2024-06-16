import { mount } from "@vue/test-utils";
import Home from "@/views/Home.vue";
import { createStore } from "vuex";

const mockStore = createStore({
    state: {
        user: {
            data: {
                a: true,
            },
        },
    },
});
describe("HomePage", () => {
    const wrapper = mount(Home, {
        shallow: true,
        global: {
            plugins: [mockStore],
            mocks: {
                $route: {},
                $router: {},
            },
            // stubs: ["router-link"],
        },
    });
    test("renders a homePage", () => {
        const homeTitle = wrapper.get("h1");

        expect(homeTitle.text()).toBe("BlogPage");
        expect(homeTitle.text()).not.toBe("BlogPagea");

        const p = wrapper.get("p");
        expect(p.text()).toMatch(/Description for few sentences./);

        const routerLink = wrapper.get("router-link");
        expect(routerLink.text()).toMatch(/Get Started/);
    });
});
