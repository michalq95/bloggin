<template>
    <div v-if="!donations">Loading...</div>
    <div class="max-w-4xl m-auto flex gap-x-4 justify-center" v-else>
        <div
            v-for="donation in donations"
            :class="
                donation.id == activeDonation ? 'bg-indigo-200' : 'bg-white'
            "
            class="px-10 py-6 rounded-lg shadow-md text-black"
        >
            <h3>{{ donation.name }}</h3>
            <p>{{ donation.price }} pln</p>
            <button
                @click="setDonation(donation.id)"
                class="hover:bg-gray-500 mx-1 px-1"
            >
                Donate
            </button>
        </div>
    </div>
    <form id="payment-form">
        <div id="payment-element">
            <!-- Stripe will create form elements here -->
        </div>
        <button v-if="stripe" type="submit" @click.prevent="handleSubmit">
            Pay via Stripe
        </button>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
    getDonations,
    initiateDonation,
    donationSuccess,
    donationFailure,
} from "../service";

const router = useRouter();

const donations = ref([]);
const activeDonation = ref(null);
const stripe = ref(null);
const elements = ref(null);

async function setDonation(id) {
    const res = await initiateDonation(id);
    try {
        stripe.value = window.Stripe(import.meta.env.VITE_STRIPE_PUBKEY);

        const options = {
            clientSecret: res.data.client_secret,
        };

        elements.value = stripe.value.elements(options);
        const paymentElement = elements.value.create("payment");
        paymentElement.mount("#payment-element");
        activeDonation.value = id;
    } catch (e) {
        console.error(e);
    }
}
async function tempsend() {
    await donationSuccess("random value");
}
async function handleSubmit() {
    const { error } = await stripe.value.confirmPayment({
        elements: elements.value,
        redirect: "if_required",
    });

    if (error === undefined) {
        router.push({ name: "Thanks" });
    } else {
        console.log("Something went wrong, you were not charged");
    }
}

onMounted(async () => {
    const data = await getDonations();
    donations.value = data.data;
});
</script>
