import { mount, flushPromises } from "@vue/test-utils";
import { ref } from "vue";
import ImageComponent from "@/views/RandomImage.vue";
import { getRandomImage } from "@/service";

jest.mock("@/service", () => ({
    getRandomImage: jest.fn(),
}));

describe("Component.vue", () => {
    it("renders the image and answer correctly", async () => {
        const mockData = {
            data: {
                image: "http://example.com/image.jpg",
                answer: "Mock Answer",
            },
        };
        getRandomImage.mockResolvedValue(mockData);

        const wrapper = mount(ImageComponent);

        expect(getRandomImage).toHaveBeenCalled();
        await flushPromises();
        await wrapper.vm.$nextTick();

        const img = wrapper.find("img");
        const text = wrapper.text();

        expect(img.attributes("src")).toBe(mockData.data.image);
        expect(text).toContain(mockData.data.answer);
    });
});
